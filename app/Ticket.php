<?php

namespace App;

use App\Events\TicketsChanged;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Ticket extends Model
{
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
