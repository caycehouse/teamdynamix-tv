<?php

namespace App;

use App\Events\TicketsChanged;
use Carbon\Carbon;
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
    protected $fillable = ['ticket_id', 'title', 'status', 'lab', 'ticket_created_at', 'color_code', 'resolved_by', 'resolved_at'];

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
                'username' => env('TD_USERNAME'),
                'password' => env('TD_PASSWORD')
            ]
        ])->getBody();

        $response = $client->request('GET', "https://ecu.teamdynamix.com/TDWebApi/api/reports/110937", [
            'headers' => [ 'Authorization' => 'Bearer ' . $authToken ],
            'query' => [ 'withData' => 'true' ]
        ])->getBody();

        $json_response = json_decode($response, true);

        foreach($json_response['DataRows'] as $jr) {
            $resolvedAt = Carbon::parse($jr['ClosedDate']);
            $resolvedAt->setTimezone('America/New_York');

            $createdAt = Carbon::parse($jr['CreatedDate']);
            $createdAt->setTimezone('America/New_York');

            $colorCode = '';
            if($jr['StatusName'] === 'New') {
                if($createdAt <= Carbon::now('America/New_York')->subHours(12)) {
                    $colorCode = 'text-warning';
                }

                if($createdAt <= Carbon::now('America/New_York')->subHours(24)) {
                    $colorCode = 'text-danger';
                }
            }

            Ticket::updateOrCreate(
                [
                    'ticket_id' => $jr['TicketID']
                ],
                [
                    'title' => $jr['Title'],
                    'status' => $jr['StatusName'],
                    'lab' => empty($jr['18375'])? '' : $jr['18375'],
                    'ticket_created_at' => $createdAt->format('Y-m-d H:i:s'),
                    'color_code' => $colorCode,
                    'resolved_by' => empty($jr['ClosedByFullName'])? '' : $jr['ClosedByFullName'],
                    'resolved_at' => $resolvedAt->format('Y-m-d H:i:s')
                ]
            );
        }

        event(new TicketsChanged);
    }
}
