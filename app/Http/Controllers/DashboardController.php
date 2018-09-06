<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

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
        $fromDate = Carbon::now()->subDay()->startOfWeek()->toDateString();
        $tillDate = Carbon::now()->subDay()->toDateString();

        $tickets = Ticket::unresolved()->orderBy('ticket_created_at', 'desc')->get();
        $printers = Printer::all();
        $stats = Ticket::resolved()->whereBetween( DB::raw('date(ticket_created_at)'), [$fromDate, $tillDate] )
            ->groupBy('resolved_by')->select('resolved_by', DB::raw('count(*) as total'))->orderBy('total', 'desc')->get();

        return view('dashboard.index', ['tickets' => $tickets, 'printers' => $printers, 'stats' => $stats]);
    }
}