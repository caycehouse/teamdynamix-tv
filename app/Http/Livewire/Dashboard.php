<?php

namespace App\Http\Livewire;

use App\Models\Device;
use App\Models\PapercutStatuses;
use App\Models\Printer;
use App\Models\Resolution;
use App\Models\Ticket;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $resp_group = '+Labs & Advanced Technologies';
        $tickets = Ticket::unresolved()->byResponsibleGroup($resp_group)->orderByRaw("CAST(age as UNSIGNED) ASC")->get();
        $resolutionsLastWeek = Resolution::byResponsibleGroup($resp_group)->orderBy('closes', 'desc')->lastWeek()->get();
        $resolutionsThisWeek = Resolution::byResponsibleGroup($resp_group)->orderBy('closes', 'desc')->thisWeek()->get();

        $devices = Device::inError()->get();
        $papercutStatuses = PapercutStatuses::all();
        $printers = Printer::inError()->get();

        return view('livewire.dashboard', [
            'tickets' => $tickets,
            'devices' => $devices,
            'papercutStatuses' => $papercutStatuses,
            'printers' => $printers,
            'resolutionsLastWeek' => $resolutionsLastWeek,
            'resolutionsThisWeek' => $resolutionsThisWeek,
        ]);
    }
}
