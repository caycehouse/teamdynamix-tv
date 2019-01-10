<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Device;
use App\Ticket;
use App\PapercutStatuses;
use App\Printer;
use App\Stats;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show the dashboard.
     *
     * @return Response
     */
    public function __invoke()
    {
        $devices = Device::inError()->get();
        $tickets = Ticket::unresolved()->get();
        $papercutStatuses = PapercutStatuses::all();
        $printers = Printer::inError()->get();

        $fromDate = Carbon::now()->startOfWeek()->toDateTimeString();
        $tillDate = Carbon::now()->toDateTimeString();
        $stats = Ticket::resolved()->get();

        return view('dashboard.index', [
            'devices' => $devices,
            'tickets' => $tickets,
            'papercutStatuses' => $papercutStatuses,
            'printers' => $printers,
            'stats' => $stats
        ]);
    }
}
