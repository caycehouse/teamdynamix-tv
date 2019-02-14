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
     * Shows dashboard not found message.
     *
     * @return Response
     */
    public function index()
    {
        return view('dashboard.index');
    }

    /**
     * Show the dashboard for the provided group name.
     *
     * @return Response
     */
    public function byGroup($resp_group)
    {
        $tickets = Ticket::unresolved()->byResponsibleGroup($resp_group)->get();
        $resolutionsLastWeek = Resolution::byResponsibleGroup($resp_group)->lastWeek()->get();
        $resolutionsThisWeek = Resolution::byResponsibleGroup($resp_group)->thisWeek()->get();

        if ('+Student Computer Labs' == $resp_group) {
            $devices = Device::inError()->get();
            $papercutStatuses = PapercutStatuses::all();
            $printers = Printer::inError()->get();

            $fromDate = Carbon::now()->startOfWeek()->toDateTimeString();
            $tillDate = Carbon::now()->toDateTimeString();

            return view('dashboard.byGroup', [
                'devices' => $devices,
                'tickets' => $tickets,
                'papercutStatuses' => $papercutStatuses,
                'printers' => $printers,
                'resolutionsLastWeek' => $resolutionsLastWeek,
                'resolutionsThisWeek' => $resolutionsThisWeek,
            ]);
        } else {
            return view('dashboard.byGroup', [
                'tickets' => $tickets,
                'resolutionsLastWeek' => $resolutionsLastWeek,
                'resolutionsThisWeek' => $resolutionsThisWeek,
            ]);
        }
    }
}
