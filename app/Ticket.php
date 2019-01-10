<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use GuzzleHttp\Client;

class Ticket extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['ticket_id', 'title', 'status', 'lab', 'age'];

    /**
     * Scope a query to only include resolved tickets.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeResolved($query)
    {
        return $query->whereIn('status', ['Closed']);
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

    /**
     * Gets new tickets from TeamDynamix.
     *
     * @return void
     */
    public static function getNew()
    {
        $client = new Client();

        $authToken = $client->request('POST', 'https://ecu.teamdynamix.com/TDWebApi/api/auth', [
            'json' => [
                'username' => config('labtechs.td_username'),
                'password' => config('labtechs.td_password')
            ]
        ])->getBody();

        $response = $client->request('GET', "https://ecu.teamdynamix.com/TDWebApi/api/reports/110937", [
            'headers' => ['Authorization' => 'Bearer ' . $authToken],
            'query' => ['withData' => 'true']
        ])->getBody();

        $json_response = json_decode($response, true);

        Ticket::truncate();

        foreach ($json_response['DataRows'] as $jr) {
            $ticket = Ticket::create(
                [
                    'ticket_id' => $jr['TicketID'],
                    'title' => $jr['Title'],
                    'lab' => empty($jr['18375']) ? '' : $jr['18375'],
                    'status' => $jr['StatusName'],
                    'age' => "{$jr['DaysOld']} d"
                ]
            );

            $ticket->save();
        }
    }
}
