<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GuzzleHttp\Client;

class Resolution extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'closes', 'period'];

    /**
     * Gets resolutions for week and url provided.
     *
     * @return void
     */
    public static function getResolutionForWeek($period, $url)
    {
        $client = new Client();

        $authToken = $client->request('POST', 'https://ecu.teamdynamix.com/TDWebApi/api/auth', [
            'json' => [
                'username' => config('labtechs.td_username'),
                'password' => config('labtechs.td_password')
            ]
        ])->getBody();

        $response = $client->request('GET', $url, [
            'headers' => ['Authorization' => 'Bearer ' . $authToken],
            'query' => ['withData' => 'true']
        ])->getBody();

        $json_response = json_decode($response, true);

        foreach ($json_response['DataRows'] as $jr) {
            $ticket = Resolution::create(
                [
                    'name' => $jr['ClosedByFullName'],
                    'closes' => $jr['CountTicketID'],
                    'period' => $period
                ]
            );
        }
    }

    /**
     * Gets new tickets from TeamDynamix.
     *
     * @return void
     */
    public static function getResolutions()
    {
        Resolution::truncate();
        self::getResolutionForWeek('last_week', 'https://ecu.teamdynamix.com/TDWebApi/api/reports/118131');
        self::getResolutionForWeek('this_week', 'https://ecu.teamdynamix.com/TDWebApi/api/reports/117033');
    }
}
