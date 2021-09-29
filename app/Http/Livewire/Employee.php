<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Employee extends Component
{
    public $employees, $name, $banner_id, $selected_id;
    public $updateMode = false;

    public function render()
    {
        $this->employees = \App\Models\Employee::all();
        return view('employee.index');
    }

    private function resetInput()
    {
        $this->name = null;
        $this->banner_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required|min:5',
            'banner_id' => 'required|unique:employees'
        ]);

        \App\Models\Employee::create([
            'name' => $this->name,
            'banner_id' => $this->banner_id
        ]);

        $this->resetInput();
    }

    public function edit($id)
    {
        $employee = \App\Models\Employee::findOrFail($id);
        $this->selected_id = $id;
        $this->name = $employee->name;
        $this->banner_id = $employee->banner_id;
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'selected_id' => 'required|numeric',
            'name' => 'required|min:5',
            'banner_id' => 'required|unique:employees'
        ]);

        if ($this->selected_id) {
            $employee = \App\Models\Employee::find($this->selected_id);
            $employee->update([
                'name' => $this->name,
                'banner_id' => $this->banner_id
            ]);

            $this->resetInput();
            $this->updateMode = false;
        }
    }
}
