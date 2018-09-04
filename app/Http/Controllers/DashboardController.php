<?php

namespace App\Http\Controllers;

use App\Ticket;
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
        $tickets = Ticket::orderBy('ticket_created_at', 'desc')->get();

        return view('dashboard.index', ['tickets' => $tickets]);
    }
}