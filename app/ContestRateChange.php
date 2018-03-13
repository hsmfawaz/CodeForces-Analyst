<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContestRateChange extends Model
{
    protected $table = "ContestRateChange";
    protected $guarded = [];
    protected $dates = ['created_at'];

    public function TopUser()
    {
        return $this->belongsTo(Users::class,'handle','handle');
    }
}
