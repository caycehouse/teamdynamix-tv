<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Carbon\Carbon;

use App\Ticket;
use App\Printer;
use App\Http\Controllers\Controller;
use App\PapercutStatuses;

class DashboardController extends Controller
{
    /**
     * Show the dashboard.
     *
     * @return Response
     */
    public function __invoke()
    {
        $tickets = Ticket::unresolved()->orderBy('ticket_created_at', 'desc')->get();
        $printers = Printer::inError()->get();
        $papercutStatuses = PapercutStatuses::all();

        $fromDate = Carbon::now()->startOfWeek()->toDateTimeString();
        $tillDate = Carbon::now()->toDateTimeString();

        $stats = Ticket::resolved()->whereBetween('resolved_at', [$fromDate, $tillDate])
            ->groupBy('resolved_by')->select('resolved_by', DB::raw('count(*) as total'))->orderBy('total', 'desc')->get();

        return view( 'dashboard.index', [
                'tickets' => $tickets,
                'printers' => $printers,
                'stats' => $stats,
                'papercutStatuses' => $papercutStatuses
            ]
        );
    }
}
