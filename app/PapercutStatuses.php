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
     * Gets the overall papercut status summary.
     *
     * @return void
     */
    public static function getPapercutStatusSummary()
    {
        $client = new Client(['http_errors' => false]);
        $response = $client->request('GET', 'http://pirateprint.ecu.edu:9191/api/health/site-servers/status', [
            'query' => ['Authorization' => env('PAPERCUT_AUTH_TOKEN')]
        ])->getBody();

        $json_response = json_decode($response);

        $statusColor = 'text-success';
        if ($json_response->status !== 'OK') {
            $statusColor = 'text-danger';
        }

        $ps = PapercutStatuses::firstOrNew([
            'status_name' => 'Papercut System'
        ]);

        $ps->fill([
            'status' => $json_response->status,
            'status_color' => $statusColor
        ]);

        if ($ps->isDirty()) {
            $ps->save();
        }
    }

    /**
     * Gets the print providers summary.
     *
     * @return void
     */
    public static function getPrintProvidersSummary()
    {
        $client = new Client(['http_errors' => false]);
        $response = $client->request('GET', 'http://pirateprint.ecu.edu:9191/api/health/print-providers/status', [
            'query' => ['Authorization' => env('PAPERCUT_AUTH_TOKEN')]
        ])->getBody();

        $json_response = json_decode($response);

        $statusColor = 'text-success';
        if ($json_response->status !== 'OK') {
            $statusColor = 'text-danger';
        }

        $ps = PapercutStatuses::firstOrNew([
            'status_name' => 'Print Providers'
        ]);

        $ps->fill([
            'status' => $json_response->status,
            'status_color' => $statusColor
        ]);

        if ($ps->isDirty()) {
            $ps->save();
        }
    }

    /**
     * Gets the web-print servers summary.
     *
     * @return void
     */
    public static function getWebPrintServersSummary()
    {
        $client = new Client(['http_errors' => false]);
        $response = $client->request('GET', 'http://pirateprint.ecu.edu:9191/api/health/web-print/status?servers-in-error-threshold=0', [
            'query' => ['Authorization' => env('PAPERCUT_AUTH_TOKEN')]
        ])->getBody();

        $json_response = json_decode($response);

        $statusColor = 'text-success';
        if ($json_response->status !== 'OK') {
            $status = 'text-danger';
        }


        $ps = PapercutStatuses::firstOrNew([
            'status_name' => 'Web-Print Servers'
        ]);

        $ps->fill([
            'status' => $json_response->status,
            'status_color' => $statusColor
        ]);

        if ($ps->isDirty()) {
            $ps->save();
        }
    }

    /**
     * Gets the mobility print servers summary.
     *
     * @return void
     */
    public static function getMobilityPrintServersSummary()
    {
        $client = new Client(['http_errors' => false]);
        $response = $client->request('GET', 'http://pirateprint.ecu.edu:9191/api/health/mobility-print-servers/status', [
            'query' => ['Authorization' => env('PAPERCUT_AUTH_TOKEN')]
        ])->getBody();

        $json_response = json_decode($response);

        $statusColor = 'text-success';
        if ($json_response->status !== 'OK') {
            $statusColor = 'text-danger';
        }

        $ps = PapercutStatuses::firstOrNew([
            'status_name' => 'Mobility-Print Servers'
        ]);

        $ps->fill([
            'status' => $json_response->status,
            'status_color' => $statusColor
        ]);

        if ($ps->isDirty()) {
            $ps->save();
        }
    }

    public static function getStats()
    {
        self::getPapercutStatusSummary();
        self::getPrintProvidersSummary();
        self::getWebPrintServersSummary();
        self::getMobilityPrintServersSummary();
    }
}
