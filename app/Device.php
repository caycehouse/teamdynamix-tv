<?php

namespace App;

use App\Events\DevicesChanged;
use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'status'];


    /**
     * Scope a query to only include devices in error.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInError($query)
    {
        return $query->whereNotIn('status', ['OK'])->orderBy('name', 'desc');
    }

    public static function getStats()
    {
        $client = new Client();

        $response = $client->request('GET', 'http://pirateprint.ecu.edu:9191/api/health/devices', [
            'query' => ['Authorization' => env('PAPERCUT_AUTH_TOKEN')]
        ])->getBody();

        $json_response = json_decode($response);

        foreach ($json_response->devices as $jr) {
            Device::updateOrCreate(
                [
                    'name' => $jr->name
                ],
                [
                    'status' => $jr->state->status
                ]
            );
        }

        event(new DevicesChanged);
    }
}
