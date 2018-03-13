<?php

namespace App\Classes;


use App\ContestRateChange;
use App\Problems;
use App\Submissions;
use App\Users;
use Carbon\Carbon;

class Contest
{
    public $id;
    public $limit;
    public $rate;
    public $handle;

    public function __construct($id, $limit, $handle, $rate)
    {
        $this->id = $id;
        $this->limit = $limit;
        $this->handle = $handle;
        $this->rate = $rate;
    }


    public static function GetUser($handle)
    {
        $data = Users::where('handle', $handle)->first();
        if ($data)
            return true;
        $data = @file_get_contents('http://codeforces.com/api/user.info?handles=' . $handle);
        $data = json_decode($data);
        if (isset($data->status) and $data->status == "OK") {
            $data = $data->result[0];
            Users::create([
                'name' => $data->firstName . " " . $data->lastName,
                'maxrate' => $data->maxRating,
                'maxrank' => $data->maxRank,
                'rate' => $data->rating,
                'rank' => $data->rank,
                'avatar' => $data->titlePhoto,
                'handle' => $handle
            ]);
            return true;
        } else
            return false;
    }

    public function GetData()
    {
        if ($this->rate != -1)
            return $this->GetContestUsers($this->handle);
        return null;
    }

    public static function GetContestStartTime($id)
    {
        $data = @file_get_contents("http://codeforces.com/api/contest.standings?contestId=$id&from=1&count=1");
        $data = json_decode($data);
        if (isset($data->status) and $data->status == "OK") {
            return $data->result->contest->startTimeSeconds;
        } else
            return time();
    }

    public static function GetRateRange($rate)
    {
        if ($rate >= 0 and $rate <= 1199) return [0, 1199];
        elseif ($rate >= 1200 and $rate <= 1399) return [1200, 1399];
        elseif ($rate >= 1400 and $rate <= 1599) return [1400, 1599];
        elseif ($rate >= 1600 and $rate <= 1899) return [1600, 1899];
        elseif ($rate >= 1900 and $rate <= 2299) return [1900, 2299];
        elseif ($rate >= 2300 and $rate <= 2399) return [2300, 2399];
        elseif ($rate >= 2400 and $rate <= 2599) return [2400, 2599];
        else return [2600, 5000];
    }

    public static function GetUserRate($handle, $id)
    {
        $data = @file_get_contents('http://codeforces.com/api/user.rating?handle=' . $handle);
        $data = json_decode($data);
        if (isset($data->status) and $data->status == 'OK') {
            foreach ($data->result as $item) {
                if ($item->contestId == $id) {
                    return $item->oldRating;
                }
            }
        }
        return -1;
    }

    public function ImportUserSubmissions($id, $curtime)
    {
        $data = Submissions::where('handle', $id)->max('sent_at');
        $curtime = Carbon::createFromTimestamp($curtime)->toDateTimeString();
        if (isset($data) and $data >= $curtime)
            return true;

        $data = @file_get_contents('http://codeforces.com/api/user.status?handle=' . $id);
        $data = json_decode($data);
        if (isset($data->status) and $data->status == "OK") {
            Submissions::where('handle', $id)->delete();
            $inserted = [];
            foreach ($data->result as $item) {
                $prob = $item->problem;
                if ($item->verdict == "OK" and !isset($inserted[$prob->contestId . $prob->index])) {
                    $inserted[$prob->contestId . $prob->index] = 1;
                    $problem = Problems::where('contestid', $prob->contestId)->where('index', $prob->index)->first();
                    if ($problem) {
                        Submissions::create([
                            'handle' => $id,
                            'problem' => $problem->id,
                            'sent_at' => $item->creationTimeSeconds
                        ]);
                    }
                }
            }
            return true;
        } else
            return false;
    }


    public function GetContestUsers($handle)
    {
        return ContestRateChange::where('contestid', $this->id)
            ->whereBetween('oldRating',self::GetRateRange($this->rate))->limit($this->limit)->get();
    }
}