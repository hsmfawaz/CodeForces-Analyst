<?php

namespace App\Jobs;

use App\Classes\Contest;
use App\Reports;
use App\ReportsProblems;
use App\Submissions;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class WorkOnRequest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $data;

    public function __construct($arr)
    {
        $this->data = $arr;
    }

    public function handle()
    {
        $d = $this->data;
        echo "Started report number " . $d['report'] . PHP_EOL;

        $curr = Reports::find($d['report']);
        $curr->update(['status' => 1]);
        $contest = new Contest($d['contest'], $d['limit'], $d['handle'], $d['rate']);
        $data = $contest->GetData();

        if ($data) {
            $time = Contest::GetContestStartTime($d['contest']);
            $contest->ImportUserSubmissions($d['handle'], $time);
            $userSubmissions = Submissions::where('handle', $d['handle'])->select('problem')->get()->toArray();

            foreach ($data as $user) {
                if ($contest->ImportUserSubmissions($user->handle, $time)) {
                    $dt = Submissions::where('handle', $user->handle)->whereNotIn('problem', $userSubmissions)->select('problem')->get();

                    foreach ($dt as $t) {
                        $db = ReportsProblems::where('report', $d['report'])->where('problem', $t->problem)->first();
                        if ($db)
                            $db->increment('count');
                        else {
                            ReportsProblems::create([
                                'count' => 1,
                                'report' => $d['report'],
                                'problem' => $t->problem
                            ]);
                        }
                    }
                }
            }
            $ds = ReportsProblems::where('report', $d['report'])->orderBy('count', 'desc')
                ->limit($d['sheet'])->select('id')->get()->toArray();
            ReportsProblems::where('report', $d['report'])->whereNotIn('id', $ds)->delete();
            $curr->update(['status' => 2]);
            dispatch((new RemoveReport($d['report']))->delay(now()->addHours(24)));
            echo "Finished Report " . $d['report'] . PHP_EOL;
        } else
            echo 'Cant get users' . PHP_EOL;
    }
}
