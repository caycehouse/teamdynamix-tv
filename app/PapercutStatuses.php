<?php

namespace App;

use App\Events\PapercutStatusesChanged;
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
        $client = new Client();
        $response = $client->request('GET', 'http://pirateprint.ecu.edu:9191/api/health/site-servers/status', [
            'query' => ['Authorization' => env('PAPERCUT_AUTH_TOKEN')]
        ])->getBody();

        $json_response = json_decode($response);

        $statusColor = 'text-success';
        if ($json_response->status !== 'OK') {
            $statusColor = 'text-danger';
        }

        PapercutStatuses::updateOrCreate(
            [
                'status_name' => 'MGMT System Health'
            ],
            [
                'status' => $json_response->status,
                'status_color' => $statusColor
            ]
        );
    }

    /**
     * Gets the print providers summary.
     *
     * @return void
     */
    public static function getPrintProvidersSummary()
    {
        $client = new Client();
        $response = $client->request('GET', 'http://pirateprint.ecu.edu:9191/api/health/print-providers/status', [
            'query' => ['Authorization' => env('PAPERCUT_AUTH_TOKEN')]
        ])->getBody();

        $json_response = json_decode($response);

        $statusColor = 'text-success';
        if ($json_response->status !== 'OK') {
            $statusColor = 'text-danger';
        }

        PapercutStatuses::updateOrCreate(
            [
                'status_name' => 'Print Providers Health'
            ],
            [
                'status' => $json_response->status,
                'status_color' => $statusColor
            ]
        );
    }
    /**
     * Gets the web-print servers summary.
     *
     * @return void
     */
    public static function getWebPrintServersSummary()
    {
        $client = new Client();
        $response = $client->request('GET', 'http://pirateprint.ecu.edu:9191/api/health/web-print/status?servers-in-error-threshold=0', [
            'query' => ['Authorization' => env('PAPERCUT_AUTH_TOKEN')]
        ])->getBody();

        $json_response = json_decode($response);

        $statusColor = 'text-success';
        if ($json_response->status !== 'OK') {
            $status = 'text-danger';
        }

        PapercutStatuses::updateOrCreate(
            [
                'status_name' => 'Web-Print Servers Health'
            ],
            [
                'status' => $json_response->status,
                'status_color' => $statusColor
            ]
        );
    }

    /**
     * Gets the mobility print servers summary.
     *
     * @return void
     */
    public static function getMobilityPrintServersSummary()
    {
        $client = new Client();
        $response = $client->request('GET', 'http://pirateprint.ecu.edu:9191/api/health/mobility-print-servers/status', [
            'query' => ['Authorization' => env('PAPERCUT_AUTH_TOKEN')]
        ])->getBody();

        $json_response = json_decode($response);

        $statusColor = 'text-success';
        if ($json_response->status !== 'OK') {
            $statusColor = 'text-danger';
        }

        PapercutStatuses::updateOrCreate(
            [
                'status_name' => 'Mobility-Print Servers Health'
            ],
            [
                'status' => $json_response->status,
                'status_color' => $statusColor
            ]
        );
    }

    public static function getStats()
    {
        self::getPapercutStatusSummary();
        self::getPrintProvidersSummary();
        self::getWebPrintServersSummary();
        self::getMobilityPrintServersSummary();

        event(new PapercutStatusesChanged);
    }
}
