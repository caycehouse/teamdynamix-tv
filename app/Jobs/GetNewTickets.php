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
use App\Events\StatsChanged;

class GetNewTickets implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $root_url;
    protected $report_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->root_url = 'https://ecu.teamdynamix.com/TDWebApi';
        $this->report_id = '110937';
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
        $response = $client->request('GET', "{$this->root_url}/api/reports/{$this->report_id}", [
            'headers' => [ 'Authorization' => 'Bearer ' . self::get_auth_token() ],
            'query' => [ 'withData' => 'true' ]
        ])->getBody();

        $json_response = json_decode($response, true);

        foreach($json_response['DataRows'] as $jr) {
            $createdAt = Carbon::parse($jr['CreatedDate']);
            $createdAt->setTimezone('America/New_York');

            $colorCode = '';
            if($jr['StatusName'] === 'New') {
                if($createdAt <= Carbon::now('America/New_York')->subHours(12)) {
                    $colorCode = 'text-warning';
                }
                
                if($createdAt <= Carbon::now('America/New_York')->subHours(24)) {
                    $colorCode = 'text-danger';
                }
            }

            Ticket::updateOrCreate(
                [
                    'ticket_id' => $jr['TicketID']
                ],
                [
                    'title' => $jr['Title'],
                    'status' => $jr['StatusName'],
                    'lab' => empty($jr['18375'])? '' : $jr['18375'],
                    'ticket_created_at' => $createdAt->format('Y-m-d H:i:s'),
                    'color_code' => $colorCode,
                    'resolved_by' => empty($jr['ClosedByFullName'])? '' : $jr['ClosedByFullName']
                ]
            );
        }
        
        event(new TicketsChanged);
        event(new StatsChanged);
    }
}
