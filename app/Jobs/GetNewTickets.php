<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use GuzzleHttp\Client;
use Carbon\Carbon;

use App\Ticket;
use App\Events\TicketsChanged;

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
            $createdAt = Carbon::parse($jr->CreatedDate);
            $createdAt->setTimezone('America/New_York');

            $colorCode = '';
            if($jr->StatusName === 'New') {
                if($createdAt <= Carbon::now('America/New_York')->subHours(12)) {
                    $colorCode = 'text-warning';
                }
                
                if($createdAt <= Carbon::now('America/New_York')->subHours(24)) {
                    $colorCode = 'text-danger';
                }
            }

            Ticket::updateOrCreate(
                [
                    'ticket_id' => $jr->ID
                ],
                [
                    'title' => $jr->Title,
                    'status' => $jr->StatusName,
                    'lab' => $jr->LocationName,
                    'ticket_created_at' => $createdAt->format('Y-m-d H:i:s'),
                    'color_code' => $colorCode
                ]
            );
        }
        
        event(new TicketsChanged);
    }
}
