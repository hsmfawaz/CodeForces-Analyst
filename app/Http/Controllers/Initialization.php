<?php

namespace App\Http\Controllers;

use App\ContestRateChange;
use App\Jobs\AddContest;
use App\Problems;
use Carbon\Carbon;
use Illuminate\Http\Request;

class Initialization extends Controller
{
    public function problems($pass)
    {
        if ($pass != "deepweb")
            return "You are not allowed to visit this page";

        $data = file_get_contents('http://codeforces.com/api/problemset.problems');
        $data = json_decode($data)->result->problems;
        foreach ($data as $problem) {
            $tags = "";
            for ($i = 0; $i < count($problem->tags); $i++)
                $tags .= ($i > 0 ? ',' : '') . $problem->tags[$i];
            $validate = Problems::where('contestId', $problem->contestId)->where('index', $problem->index)->count();
            if ($validate == 0)
                Problems::create([
                    'contestId' => $problem->contestId,
                    'index' => $problem->index,
                    'name' => $problem->name,
                    'tags' => $tags,
                ]);
        }
        echo "Importing Problems information Done.";
    }

    public function GetAllContests($pass)
    {
        if ($pass != "deepweb")
            return "You are not allowed to visit this page";
        $data = @file_get_contents('http://codeforces.com/api/contest.list');
        $data = json_decode($data);
        if (isset($data->status) and $data->status == "OK") {
            foreach ($data->result as $contest) {
                dispatch(new AddContest($contest->id));
            }
        }
    }
}
