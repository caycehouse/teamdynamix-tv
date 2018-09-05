<?php

namespace App;

use App\Events\PrintersChanged;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Printer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'status'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('open', function (Builder $builder) {
            $builder->whereNotIn('status', ['OK'])
                ->orderBy('name', 'desc');
        });
    }
}
