<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reports extends Model
{
    protected $guarded = [];
    protected $dates = ['created_at'];

    public function problems()
    {
        return $this->hasMany(ReportsProblems::class, 'report');
    }

    public function user()
    {
        return $this->belongsTo(Users::class, 'handle', 'handle');
    }


    public function Contest(){
        return $this->hasMany(ContestRateChange::class,'contestid','contestid');
    }

}
