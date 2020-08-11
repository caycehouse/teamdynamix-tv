<?php

namespace App\Http\Livewire;

use App\Device;
use App\PapercutStatuses;
use App\Printer;
use App\Resolution;
use App\Ticket;
use Livewire\Component;

class Dashboard extends Component
{
    public function render()
    {
        $resp_group = '+Labs & Advanced Technologies';
        $tickets = Ticket::unresolved()->byResponsibleGroup($resp_group)->get();
        $resolutionsLastWeek = Resolution::byResponsibleGroup($resp_group)->lastWeek()->get();
        $resolutionsThisWeek = Resolution::byResponsibleGroup($resp_group)->thisWeek()->get();

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
