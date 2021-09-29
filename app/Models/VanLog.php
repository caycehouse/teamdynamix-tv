<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VanLog extends Model
{
    use SoftDeletes;

    /**
    * Get the van of the van log.
    */
    public function van()
    {
        return $this->belongsTo('App\Models\Van')->withDefault();
    }

    /**
    * Get the employee of the van log.
    */
    public function employee()
    {
        return $this->belongsTo('App\Models\Employee')->withDefault();
    }
}
