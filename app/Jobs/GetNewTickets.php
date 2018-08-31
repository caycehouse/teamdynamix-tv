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
    private $auth_token;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->root_url = 'https://ecu.teamdynamix.com/TDWebApi';
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
        get_auth_token();
    }
}
