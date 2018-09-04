<?php

namespace App\Http\Controllers;

use App\Ticket;
use App\Printer;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    /**
     * Show the dashboard.
     *
     * @return Response
     */
    public function __invoke()
    {
        $tickets = Ticket::all();
        $printers = Printer::all();

        return view('dashboard.index', ['tickets' => $tickets, 'printers' => $printers]);
    }
}