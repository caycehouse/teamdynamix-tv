<?php

namespace App\Models;

use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class Resolution extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'closes', 'resp_group', 'period'];

    /**
     * Scope a query to only include last week's resolutions.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLastWeek($query)
    {
        $lastSunday = Carbon::now()->subWeek()->startOfWeek()->subDay();

        return $query->where('period', $lastSunday);
    }

    /**
     * Scope a query to only include this week's resolutions.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeThisWeek($query)
    {
        $thisSunday = Carbon::now()->startOfWeek()->subDay();

        return $query->where('period', $thisSunday);
    }

    /**
     * Scope a query to only include a responsible group's resolutions.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByResponsibleGroup($query, $resp_group)
    {
        return $query->where('resp_group', $resp_group);
    }

    /**
     * Gets new tickets from TeamDynamix.
     *
     * @return void
     */
    public static function getResolutions()
    {
        $client = new Client();

        $authToken = $client->request('POST', 'https://ecu.teamdynamix.com/TDWebApi/api/auth', [
            'json' => [
                'username' => config('teamdynamix.td_username'),
                'password' => config('teamdynamix.td_password'),
            ],
        ])->getBody();

        $response = $client->request('GET', 'https://ecu.teamdynamix.com/TDWebApi/api/reports/120639', [
            'headers' => ['Authorization' => 'Bearer ' . $authToken],
            'query' => ['withData' => 'true'],
        ])->getBody();

        $json_response = json_decode($response, true);

        foreach ($json_response['DataRows'] as $jr) {
            self::updateOrCreate(
                [
                    'name' => $jr['ClosedByFullName'],
                    'resp_group' => $jr['ResponsibleGroupName'],
                    'period' => Carbon::parse($jr['ClosedDate-WeekYear']),
                ],
                [
                    'closes' => $jr['CountTicketID'],
                ]
            );
        }
    }
}
