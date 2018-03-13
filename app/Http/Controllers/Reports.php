<?php

namespace App\Http\Controllers;

use App\Classes\Contest;
use App\ContestRateChange;
use App\Downloads;
use App\Jobs\WorkOnRequest;
use App\ReportsProblems;
use App\Users;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Reports as Reps;
use Illuminate\Validation\Rule;
use Excel;

class Reports extends Controller
{
    public function create(Request $req)
    {
        Contest::GetUser($req->handle);
        $this->validate($req, [
            'round' => 'required|numeric|min:1|exists:ContestRateChange,contestid',
            'users' => 'numeric|min:1|max:10',
            'problems' => 'numeric|min:1|max:100',
            'handle' => 'required|exists:users'
        ], [
            'handle.exists' => "The selected handle not found",
            'round.exists' => "The selected round not found"
        ]);
        $rate = Contest::GetUserRate($req->handle, $req->round);
        if ($rate != -1) {
            $r = Reps::where('contestid', $req->round)->where('handle', $req->handle)->first();
            $status = "OK";
            if ($r == null) {
                $r = new Reps;
                $r->handle = $req->handle;
                $r->contestid = $req->round;
                $r->limit = $req->problems;
                $r->rate = $rate;
                $r->save();
                dispatch(new WorkOnRequest([
                    'handle' => $r->handle,
                    'contest' => $r->contestid,
                    'report' => $r->id,
                    'sheet' => $req->problems,
                    'limit' => $req->users,
                    'rate' => $rate
                ]));
                $status = "queue";
            }
            return response()->json(['id' => $r->id, 'status' => $status]);
        }
        return response()->json(['id' => 0, 'status' => "OK"]);
    }

    public function get($id)
    {
        $data = Reps::findOrFail($id);
        $data2 = $data->problems()->orderBy('count', 'desc')->limit($data->limit)->with('info')->get()->toArray();
        $rate = $data->rate;
        if ($rate == 0)
            $rate = Contest::GetUserRate($data->user->handle, $data->contestid);
        $topusers = ContestRateChange::where('contestid', $data->contestid)
            ->whereBetween('oldRating', Contest::GetRateRange($rate ))->limit($data->limit)->get();
        if ($data->status == 0) {
            $res = ['status' => "queue", 'data' => []];
        } elseif ($data->status == 1) {
            $res = ['status' => "working", 'data' => []];
        } else {
            $res = ['status' => "finished", 'data' => $data2];
        }
        $res = array_merge($res, [
            'user' => $data->user,
            'contestid' => $data->contestid,
            'topusers' => $topusers
        ]);
        return response()->json($res);
    }

    public function result($id)
    {
        $data = Reps::findOrFail($id);
        $name = 'report';
        return view('welcome', compact(['data', 'name']));
    }

    public function latest()
    {
        $data = Reps::orderby('id', 'desc')->limit(20)->get()->toJson();
        $name = 'latest';
        return view('welcome', compact(['data', 'name']));
    }

    public function home()
    {
        $data = "";
        $name = 'home';
        return view('welcome', compact(['data', 'name']));
    }

    public function Download($id)
    {
        $data = Downloads::where('report', $id)->first();
        if ($data) {
            return response()->download(storage_path('exports\\' . $data->link . '.xls'));
        } else {
            $data = Reps::findOrFail($id);
            $data = $data->problems()->orderBy('count', 'desc')->limit($data->limit)->with('info')->get();
            $newdata = [];
            $links = [];
            foreach ($data as $item) {
                $newdata[] = [
                    'Problem Name' => $item->info->Name,
                    'url' => $item->info->ContestId . $item->info->index,
                    'solved' => $item->count
                ];
                $links[] = 'http://codeforces.com/contest/' . $item->info->ContestId . "/problem/" . $item->info->index;
            }
            $name = 'Sheet' . time();
            Downloads::create(['link' => $name, 'report' => $id]);
            Excel::create($name, function ($excel) use ($newdata, $links) {
                $excel->sheet('first', function ($sheet) use ($newdata, $links) {
                    $sheet->fromArray($newdata, null, 'A1', false, false);
                    $i = 0;
                    foreach ($links as $link) {
                        $sheet->getCell('B' . ++$i)->getHyperlink()->setUrl($link);
                    }
                });
            })->store('xls')->download('xls');
        }
    }
}
