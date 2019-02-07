<?php

namespace App;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class Printer extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'status', 'print_server'];

    /**
     * Scope a query to only include printers in error.
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

        $response = $client->request('GET', 'http://pirateprint.ecu.edu:9191/api/health/printers', [
            'query' => ['Authorization' => config('labtechs.papercut_auth_token')],
        ])->getBody();

        $json_response = json_decode($response);

        foreach ($json_response->printers as $jr) {
            $print_server = explode('\\', $jr->name)[0];
            if (('uniprint' === $print_server || 'papercut' === $print_server)) {
                $printer = self::firstOrNew([
                    'name' => $jr->name,
                ]);

                $printer->fill([
                    'print_server' => $print_server,
                    'status' => $jr->status,
                ]);

                if ($printer->isDirty()) {
                    $printer->save();
                }
            }
        }
    }
}
