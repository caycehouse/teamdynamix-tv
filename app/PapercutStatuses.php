<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PapercutStatuses extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['status_name', 'status'];
}
