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
Route::get('/employees', \App\Http\Livewire\Employee::class);
Route::get('/vans', \App\Http\Livewire\Van::class);

Route::get('/vanlog', function () {
    return Maatwebsite\Excel\Facades\Excel::download(new App\Exports\VanLogsExport, 'vanlogs.xlsx');
});
