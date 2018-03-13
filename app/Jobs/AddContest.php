<?php

namespace App\Jobs;

use App\ContestRateChange;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class AddContest implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */


    public function handle()
    {
        $validate = ContestRateChange::where('contestid', $this->id)->count();
        if ($validate == 0) {
            $limits = [10, 10, 10, 10, 10, 10, 10, 10];
            $data = @file_get_contents('http://codeforces.com/api/contest.ratingChanges?contestId=' . $this->id);
            $data = json_decode($data);
            if (isset($data->status) and $data->status == "OK") {
                foreach ($data->result as $item) {
                    $num = $this->GetNumber($item->oldRating);
                    if ($limits[$num] > 0) {
                        ContestRateChange::create([
                            'ContestId' => $item->contestId,
                            'handle' => $item->handle,
                            'oldRating' => $item->oldRating,
                            'newRating' => $item->newRating,
                        ]);
                        $limits[$num]--;
                    }
                }
            }
        }
        echo "Done with Contest " . $this->id . PHP_EOL;
    }
}
