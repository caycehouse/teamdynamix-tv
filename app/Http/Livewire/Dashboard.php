<?php

namespace App\Http\Livewire;

use App\Models\Device;
use App\Models\Employee;
use App\Models\PapercutStatuses;
use App\Models\Printer;
use App\Models\Resolution;
use App\Models\Ticket;
use App\Models\VanLog;
use App\Models\Van;
use Livewire\Component;

class Dashboard extends Component
{
    public $employee_id;
    public $van_id;

    public function submit()
    {
        $van = Van::find($this->van_id);
        $employee = Employee::where('banner_id', substr($this->employee_id, 0, strlen($this->employee_id)-2))->first();

        if($employee) {
            $this->validate([
                'employee_id' => 'required',
                'van_id' => 'required'
            ]);

            $van_log = new VanLog;
            $van_log->van()->associate($van);
            $van_log->employee()->associate($employee);

            if($van_log->save()) {
                $van->available = false;
                $van->save();
            }
        } else {
            $this->addError('employee_id', 'Employee not found.');
        }
        $this->reset(['van_id', 'employee_id']);
    }

    public function vanCheckIn($van)
    {
        $van_log = VanLog::find($van);
        $van_log->van->available = true;
        $van_log->van->save();
        $van_log->delete();
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
        $vans = Van::where('available', true)->get()->sortBy('name');

        return view('dashboard.dashboard', [
            'tickets' => $tickets,
            'devices' => $devices,
            'van_logs' => $van_logs,
            'vans' => $vans,
            'papercutStatuses' => $papercutStatuses,
            'printers' => $printers,
            'resolutionsLastWeek' => $resolutionsLastWeek,
            'resolutionsThisWeek' => $resolutionsThisWeek,
        ]);
    }
}
