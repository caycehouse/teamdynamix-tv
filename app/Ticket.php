<?php

namespace App;

use App\Events\StatsChanged;
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
    protected $fillable = ['ticket_id', 'title', 'status', 'lab', 'ticket_created_at', 'color_code', 'resp_group', 'resolved_by', 'resolved_at', 'age'];

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
     * Scope a query to only include student computer labs tickets.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStudentComputerLabs($query)
    {
        return $query->whereIn('resp_group', ['+Student Computer Labs']);
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

        foreach ($json_response['DataRows'] as $jr) {
            $resolvedAt = Carbon::parse($jr['ClosedDate']);
            $resolvedAt->setTimezone('America/New_York');

            $createdAt = Carbon::parse($jr['CreatedDate']);
            $createdAt->setTimezone('America/New_York');

            $colorCode = '';
            if ($jr['StatusName'] === 'New') {
                if ($createdAt <= Carbon::now('America/New_York')->subHours(24)) {
                    $colorCode = 'text-danger';
                } else if ($createdAt <= Carbon::now('America/New_York')->subHours(12)) {
                    $colorCode = 'text-warning';
                } else {
                    $colorCode = 'text-success';
                }
            } else if ($jr['StatusName'] === 'On Hold') {
                $colorCode = 'text-white-50';
            }

            $ticket = Ticket::firstOrCreate(
                [
                    'ticket_id' => $jr['TicketID']
                ],
                [
                    'title' => $jr['Title'],
                    'status' => $jr['StatusName'],
                    'lab' => empty($jr['18375']) ? '' : $jr['18375'],
                    'ticket_created_at' => $createdAt->format('Y-m-d H:i:s'),
                    'color_code' => $colorCode,
                    'resp_group' => empty($jr['ResponsibleGroupName']) ? '' : $jr['ResponsibleGroupName'],
                    'resolved_by' => empty($jr['ClosedByFullName']) ? '' : $jr['ClosedByFullName'],
                    'resolved_at' => $resolvedAt->format('Y-m-d H:i:s'),
                    'age' => "{$jr['DaysOld']} d"
                ]
            );

            if($ticket->wasRecentlyCreated) {
                event(new StatsChanged);
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
                'password' => config('labtechs.td_password')
            ]
        ])->getBody();

        $response = $client->request('GET', "https://ecu.teamdynamix.com/TDWebApi/api/217/tickets/{$this->ticket_id}", [
            'headers' => ['Authorization' => 'Bearer ' . $authToken]
        ])->getBody();

        $jr = json_decode($response, true);

        $resolvedAt = Carbon::parse($jr['CompletedDate']);
        $resolvedAt->setTimezone('America/New_York');

        $createdAt = Carbon::parse($jr['CreatedDate']);
        $createdAt->setTimezone('America/New_York');

        $colorCode = '';
        if ($jr['StatusName'] === 'New') {
            if ($createdAt <= Carbon::now('America/New_York')->subHours(24)) {
                $colorCode = 'text-danger';
            } else if ($createdAt <= Carbon::now('America/New_York')->subHours(12)) {
                $colorCode = 'text-warning';
            } else {
                $colorCode = 'text-success';
            }
        } else if ($jr['StatusName'] === 'On Hold') {
            $colorCode = 'text-white-50';
        }

        // Loop through ticket attributes.
        $lab = '';
        foreach($jr['Attributes'] as $attr) {
            // If the current attribute is of ID Lab.
            if($attr['ID'] == '18375') {
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
                'ticket_created_at' => $createdAt->format('Y-m-d H:i:s'),
                'color_code' => $colorCode,
                'resp_group' => empty($jr['ResponsibleGroupName']) ? '' : $jr['ResponsibleGroupName'],
                'resolved_by' => empty($jr['CompletedFullName']) ? '' : $jr['CompletedFullName'],
                'resolved_at' => $resolvedAt->format('Y-m-d H:i:s'),
                'age' => "{$jr['DaysOld']} d"
            ]
        );

        // If the ticket has changed.
        if (self::isDirty()) {
            // Save the updated info to the DB.
            self::save();

            // Fire update events.
            event(new StatsChanged);
        }
    }
}
