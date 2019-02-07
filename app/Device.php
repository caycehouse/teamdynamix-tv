<?php

namespace App;

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
     *
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
            'query' => ['Authorization' => config('labtechs.papercut_auth_token')],
        ])->getBody();

        $json_response = json_decode($response);

        foreach ($json_response->devices as $jr) {
            // Get device or initialize new device.
            $device = self::firstOrNew([
                'name' => $jr->name,
            ]);

            // Set the device's status.
            $device->status = $jr->state->status;

            // If device model is changed.
            if ($device->isDirty()) {
                // Save the data to the DB.
                $device->save();
            }
        }
    }
}
