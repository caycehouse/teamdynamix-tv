<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class PapercutStatuses extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['status_name', 'status', 'status_color'];

    /**
     * Pulls status for URL from papercut and saves it to DB with provided name.
     */
    public static function getPapercutStatus($url, $name)
    {
        $client = new Client(['http_errors' => false]);
        $response = $client->request('GET', $url, [
            'query' => ['Authorization' => config('labtechs.papercut_auth_token')],
        ])->getBody();

        $json_response = json_decode($response);

        $statusColor = 'text-success';
        if ('OK' !== $json_response->status) {
            $statusColor = 'text-danger';
        }

        $ps = self::firstOrNew(['status_name' => $name]);

        $ps->fill([
            'status' => $json_response->status,
            'status_color' => $statusColor,
        ]);

        if ($ps->isDirty()) {
            $ps->save();
        }
    }

    public static function getStats()
    {
        self::getPapercutStatus('http://pirateprint.ecu.edu:9191/api/health/site-servers/status',
            'Papercut System');
        self::getPapercutStatus('http://pirateprint.ecu.edu:9191/api/health/print-providers/status',
            'Print Providers');
        self::getPapercutStatus('http://pirateprint.ecu.edu:9191/api/health/web-print/status?servers-in-error-threshold=0',
            'Web-Print Servers');
        self::getPapercutStatus('http://pirateprint.ecu.edu:9191/api/health/mobility-print-servers/status',
            'Mobility-Print Servers');
    }
}
