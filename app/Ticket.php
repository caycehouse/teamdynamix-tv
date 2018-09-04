<?php

namespace App;

use App\Events\TicketsChanged;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Notifications\Notifiable;

class Ticket extends Model
{
    use Notifiable;

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'saved' => TicketsChanged::class,
        'deleted' => TicketsChanged::class
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ticket_id', 'title', 'status', 'lab', 'ticket_created_at'];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('open', function (Builder $builder) {
            $builder->whereNotIn('status', ['Closed', 'Cancelled'])
                ->orderBy('ticket_created_at', 'desc');
        });
    }
}
