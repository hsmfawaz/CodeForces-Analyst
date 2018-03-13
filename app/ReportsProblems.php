<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReportsProblems extends Model
{
    protected $table = 'ReportsProblems';
    protected $guarded = [];
    protected $dates = ['created_at'];

    public function info()
    {
        return $this->belongsTo(Problems::class, 'problem');
    }
}
