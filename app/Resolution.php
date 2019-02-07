<?php

namespace App;

use App\Events\ResolutionsChanged;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

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
                'password' => config('labtechs.td_password'),
            ],
        ])->getBody();

        $response = $client->request('GET', $url, [
            'headers' => ['Authorization' => 'Bearer '.$authToken],
            'query' => ['withData' => 'true'],
        ])->getBody();

        $json_response = json_decode($response, true);

        foreach ($json_response['DataRows'] as $jr) {
            $ticket = self::create(
                [
                    'name' => $jr['ClosedByFullName'],
                    'closes' => $jr['CountTicketID'],
                    'period' => $period,
                ]
            );
        }

        event(new ResolutionsChanged($period));
    }

    /**
     * Gets new tickets from TeamDynamix.
     *
     * @return void
     */
    public static function getResolutions()
    {
        self::truncate();
        self::getResolutionForWeek('last_week', 'https://ecu.teamdynamix.com/TDWebApi/api/reports/118131');
        self::getResolutionForWeek('this_week', 'https://ecu.teamdynamix.com/TDWebApi/api/reports/117033');
    }
}
