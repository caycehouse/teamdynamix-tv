<?php

namespace App;

use App\Events\PrintersChanged;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;

class Printer extends Model
{
    use Notifiable;

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saved' => PrintersChanged::class,
        'deleted' => PrintersChanged::class
    ];

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
