<?php

namespace App\Http\Controllers;

use App\Device;
use App\PapercutStatuses;
use App\Printer;
use App\Resolution;
use App\Ticket;
use Carbon\Carbon;

class DashboardController extends Controller
{
    /**
     * Show the dashboard.
     *
     * @return Response
     */
    public function index($resp_group)
    {
        $tickets = Ticket::unresolved()->byResponsibleGroup($resp_group)->get();
        $resolutionsLastWeek = Resolution::byResponsibleGroup($resp_group)->where('period', '=', 'last_week')->get();
        $resolutionsThisWeek = Resolution::byResponsibleGroup($resp_group)->where('period', '=', 'this_week')->get();

        if ('+Student Computer Labs' == $resp_group) {
            $devices = Device::inError()->get();
            $papercutStatuses = PapercutStatuses::all();
            $printers = Printer::inError()->get();

            $fromDate = Carbon::now()->startOfWeek()->toDateTimeString();
            $tillDate = Carbon::now()->toDateTimeString();

            return view('dashboard.index', [
                'devices' => $devices,
                'tickets' => $tickets,
                'papercutStatuses' => $papercutStatuses,
                'printers' => $printers,
                'resolutionsLastWeek' => $resolutionsLastWeek,
                'resolutionsThisWeek' => $resolutionsThisWeek,
            ]);
        } else {
            return view('dashboard.index', [
                'tickets' => $tickets,
                'resolutionsLastWeek' => $resolutionsLastWeek,
                'resolutionsThisWeek' => $resolutionsThisWeek,
            ]);
        }
    }
}
