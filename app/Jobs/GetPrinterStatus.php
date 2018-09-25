<?php

namespace App\Jobs;

use App\Printer;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GetPrinterStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Gets the overall papercut status summary.
     *
     * @return void
     */
    public function getPapercutStatusSummary()
    {
        $client = new Client();
        $response = $client->request('GET', $this->root_url . '/site-servers/status', [
            'query' => [ 'Authorization' => env('PAPERCUT_AUTH_TOKEN') ]
        ])->getBody();

        $json_response = json_decode($response);

        $statusColor = 'text-success';
        if($json_response->status !== 'OK') {
            $status = 'text-danger';
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
    public function getPrintProvidersSummary()
    {
        $client = new Client();
        $response = $client->request('GET', $this->root_url . '/print-providers/status', [
            'query' => [ 'Authorization' => env('PAPERCUT_AUTH_TOKEN') ]
        ])->getBody();

        $json_response = json_decode($response);

        $statusColor = 'text-success';
        if($json_response->status !== 'OK') {
            $status = 'text-danger';
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
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Printer::getStats();
    }
}
