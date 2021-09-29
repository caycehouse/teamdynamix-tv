<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', \App\Http\Livewire\Dashboard::class);

Route::get('/van', function () {
    $headers = [
        'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
        'Content-type'        => 'text/csv',
        'Content-Disposition' => 'attachment; filename=vans.csv',
        'Expires'             => '0',
        'Pragma'              => 'public'
    ];

    $list = \App\Models\VanLog::withTrashed()->get()->toArray();

    # add headers for each column in the CSV download
    array_unshift($list, array_keys($list[0]));

    $callback = function () use ($list) {
        $FH = fopen('php://output', 'w');
        foreach ($list as $row) {
            fputcsv($FH, $row);
        }
        fclose($FH);
    };

    return response()->stream($callback, 200, $headers);
});
