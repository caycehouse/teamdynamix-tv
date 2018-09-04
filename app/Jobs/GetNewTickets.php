<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Log;

use GuzzleHttp\Client;

use App\Ticket;

class GetNewTickets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $root_url;
    protected $app_id;

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
        ])->getBody();

        return $response;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $client = new Client();
        $response = $client->request('POST', $this->root_url . '/api/' . $this->app_id . '/tickets/search', [
            'headers' => [ 'Authorization' => 'Bearer ' . self::get_auth_token() ],
            'json' => ['PrimaryResponsibilityGroupIDs' => [ 4137 ] ]
        ])->getBody();

        $json_response = json_decode($response);

        foreach($json_response as $jr) {
            if($jr->TypeCategoryID === 2056) {
                $ticket = Ticket::updateOrCreate(
                    [
                        'ticket_id' => $jr->ID
                    ],
                    [
                        'title' => $jr->Title,
                        'status' => $jr->StatusName,
                        'lab' => $jr->LocationName,
                        'ticket_created_at' => date('Y-m-d H:i:s', strtotime($jr->CreatedDate))
                    ]
                );
            }
        }
    }
}
