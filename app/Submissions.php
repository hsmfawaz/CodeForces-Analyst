<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Submissions extends Model
{
    protected $guarded = [];
    protected $dates = ['created_at','sent_at'];
}
