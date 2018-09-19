<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use GuzzleHttp\Client;

use App\Printer;
use App\PapercutStatuses;
use App\Events\PrintersChanged;

class GetPrinterStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $root_url;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->root_url = 'http://pirateprint.ecu.edu:9191/api/health';
    }

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

        PapercutStatuses::updateOrCreate(
            [
                'status_name' => 'Status Summary'
            ],
            [
                'status' => $json_response->status
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
        // Update system status summary.
        self::getPapercutStatusSummary();

        $client = new Client();
        $response = $client->request('GET', $this->root_url . '/printers', [
            'query' => [ 'Authorization' => env('PAPERCUT_AUTH_TOKEN') ]
        ])->getBody();

        $json_response = json_decode($response);

        foreach($json_response->printers as $jr) {
            if((explode("\\", $jr->name)[0] === 'uniprint' ||  explode("\\", $jr->name)[0] === 'papercut')) {
                Printer::updateOrCreate(
                    [
                        'name' => $jr->name
                    ],
                    [
                        'print_server' => explode("\\", $jr->name)[0],
                        'status' => $jr->status,
                        'held_jobs' => $jr->heldJobsCount
                    ]
                );
            }
        }

        event(new PrintersChanged);
    }
}
