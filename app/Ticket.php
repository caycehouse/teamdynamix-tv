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
    protected $fillable = ['ticket_id', 'title', 'status', 'lab', 'ticket_created_at', 'color_code', 'resolved_by'];

    /**
     * Scope a query to only include resolved tickets.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeResolved($query)
    {
        return $query->whereIn('status', ['Closed', 'Cancelled']);
    }

    /**
     * Scope a query to only include unresolved tickets.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUnresolved($query)
    {
        return $query->whereNotIn('status', ['Closed', 'Cancelled']);
    }
}
