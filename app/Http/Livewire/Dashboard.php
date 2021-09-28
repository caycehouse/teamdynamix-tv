<?php

namespace App\Http\Livewire;

use App\Models\Device;
use App\Models\PapercutStatuses;
use App\Models\Printer;
use App\Models\Resolution;
use App\Models\Ticket;
use App\Models\Van;
use Livewire\Component;

class Dashboard extends Component
{
    public $name;
    public $user;

    public function submit()
    {
        $validatedData = $this->validate([
            'name' => 'required',
            'user' => 'required',
        ]);

        Van::create($validatedData);

        $this->reset(['name', 'user']);
    }

    public function vanCheckIn($van)
    {
        $van = Van::find($van)->delete();
    }

    public function render()
    {
        $resp_group = '+Labs & Advanced Technologies';
        $tickets = Ticket::unresolved()->byResponsibleGroup($resp_group)->orderByRaw("CAST(age as UNSIGNED) ASC")->get();
        $resolutionsLastWeek = Resolution::byResponsibleGroup($resp_group)->orderBy('closes', 'desc')->lastWeek()->get();
        $resolutionsThisWeek = Resolution::byResponsibleGroup($resp_group)->orderBy('closes', 'desc')->thisWeek()->get();

        $devices = Device::inError()->get();
        $papercutStatuses = PapercutStatuses::all();
        $printers = Printer::inError()->get();
        $vans = Van::all();

        return view('livewire.dashboard', [
            'tickets' => $tickets,
            'devices' => $devices,
            'vans' => $vans,
            'papercutStatuses' => $papercutStatuses,
            'printers' => $printers,
            'resolutionsLastWeek' => $resolutionsLastWeek,
            'resolutionsThisWeek' => $resolutionsThisWeek,
        ]);
    }
}
