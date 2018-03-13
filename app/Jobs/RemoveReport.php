<?php

namespace App\Jobs;

use App\Downloads;
use App\Reports;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Storage;

class RemoveReport implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function handle()
    {
        echo "Removing Report " . $this->id . PHP_EOL;
        Storage::delete(Downloads::where('report', $this->id)->select('link')->get()->toArray());
        Reports::find($this->id)->delete();
    }
}
