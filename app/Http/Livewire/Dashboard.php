<?php

namespace App\Http\Livewire;

use App\Models\Device;
use App\Models\PapercutStatuses;
use App\Models\Printer;
use App\Models\Resolution;
use App\Models\Ticket;
use App\Models\VanLog;
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

        VanLog::create($validatedData);

        $this->reset(['name', 'user']);
    }

    public function vanCheckIn($van)
    {
        VanLog::find($van)->delete();
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
        $van_logs = VanLog::all();

        return view('livewire.dashboard', [
            'tickets' => $tickets,
            'devices' => $devices,
            'van_logs' => $van_logs,
            'papercutStatuses' => $papercutStatuses,
            'printers' => $printers,
            'resolutionsLastWeek' => $resolutionsLastWeek,
            'resolutionsThisWeek' => $resolutionsThisWeek,
        ]);
    }
}
