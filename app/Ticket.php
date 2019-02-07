<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Ticket extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

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
     *
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
     *
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
                'password' => config('labtechs.td_password'),
            ],
        ])->getBody();

        $response = $client->request('GET', 'https://ecu.teamdynamix.com/TDWebApi/api/reports/110937', [
            'headers' => ['Authorization' => 'Bearer '.$authToken],
            'query' => ['withData' => 'true'],
        ])->getBody();

        $json_response = json_decode($response, true);

        foreach ($json_response['DataRows'] as $jr) {
            $ticket = self::withTrashed()->firstOrCreate(
                [
                    'ticket_id' => $jr['TicketID'],
                ],
                [
                    'title' => $jr['Title'],
                    'lab' => empty($jr['18375']) ? '' : $jr['18375'],
                    'status' => $jr['StatusName'],
                    'age' => $jr['DaysOld'],
                ]
            );

            // Restore deleted ticket if it has become unresolved again.
            if ($ticket->trashed()) {
                $ticket->restore();
            }
        }
    }

    /**
     * Fetches ticket information from TeamDynamix.
     *
     * @return void
     */
    public function fetch()
    {
        $client = new Client();

        $authToken = $client->request('POST', 'https://ecu.teamdynamix.com/TDWebApi/api/auth', [
            'json' => [
                'username' => config('labtechs.td_username'),
                'password' => config('labtechs.td_password'),
            ],
        ])->getBody();

        $response = $client->request('GET', "https://ecu.teamdynamix.com/TDWebApi/api/217/tickets/{$this->ticket_id}", [
            'headers' => ['Authorization' => 'Bearer '.$authToken],
        ])->getBody();

        $jr = json_decode($response, true);

        // Delete a Ticket if it belongs to another group.
        if ('+Student Computer Labs' != $jr['ResponsibleGroupName']) {
            $this->delete();
        } else {
            // Loop through ticket attributes.
            $lab = '';
            foreach ($jr['Attributes'] as $attr) {
                // If the current attribute is of ID Lab.
                if ('18375' == $attr['ID']) {
                    // Set lab equal to Value Text of the attribute.
                    $lab = $attr['ValueText'];
                }
            }

            // Fill the ticket with the fields from TeamDynamix.
            self::fill(
                [
                    'title' => $jr['Title'],
                    'status' => $jr['StatusName'],
                    'lab' => $lab,
                    'age' => $jr['DaysOld'],
                ]
            );

            // If the ticket has changed.
            if (self::isDirty()) {
                // Save the updated info to the DB.
                self::save();
            }
        }
    }
}
