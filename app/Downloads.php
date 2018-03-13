<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Downloads extends Model
{
    protected $guarded = [];
    protected $dates = ['created_at'];
}
