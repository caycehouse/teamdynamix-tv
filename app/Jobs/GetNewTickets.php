<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

use GuzzleHttp\Client;

class GetNewTickets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $root_url;
    protected $app_id;
    private $auth_token;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->root_url = 'https://ecu.teamdynamix.com/TDWebApi';
        $this->app_id = '217';
    }

    /**
     * Get the auth token.
     * 
     * @return void
     */
    public function get_auth_token()
    {
        $client = new Client();
        $response = $client->request('POST', $this->root_url . '/api/auth', [
            'json' => [
                'username' => env('TD_USERNAME'),
                'password' => env('TD_PASSWORD')
            ]
        ]);

        $this->auth_token = $response->getBody();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        self::get_auth_token();
        $client = new Client();
        $response = $client->request('GET', $this->root_url . '/tickets', [
            'headers' => [ 'Authorization' => 'Bearer ' . $this->auth_token ]
        ]);

        Log::info($response->getBody());
    }
}
