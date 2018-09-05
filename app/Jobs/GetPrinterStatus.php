<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use GuzzleHttp\Client;

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
        $this->root_url = 'http://pirateprint.ecu.edu:9192/api';
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client();
        $response = $client->request('GET', $this->root_url . '/health/printers', [
            'query' => [ 'Authorization' => env('PAPERCUT_AUTH_TOKEN') ]
        ])->getBody();

        $json_response = json_decode($response);

        foreach($json_response as $jr) {
            if((explode("/", $jr->name)[0] === 'uniprint' ||  explode("/", $jr->name)[0] === 'papercut')) {
                Printer::updateOrCreate(
                    [
                        'name' => $jr->name
                    ],
                    [
                        'status' => $jr->status,
                    ]
                );
            }
        }
    }
}
