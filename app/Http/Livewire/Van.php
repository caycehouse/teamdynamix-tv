<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Van extends Component
{
    public $employees, $name, $selected_id;
    public $updateMode = false;

    public function render()
    {
        $this->vans = \App\Models\Van::all()->sortBy('name');
        return view('van.index');
    }

    private function resetInput()
    {
        $this->name = null;
        $this->banner_id = null;
    }

    public function store()
    {
        $this->validate([
            'name' => 'required',
        ]);

        \App\Models\Van::create([
            'name' => $this->name,
            'available' => true
        ]);

        $this->resetInput();
    }

    public function edit($id)
    {
        $van = \App\Models\Van::findOrFail($id);
        $this->selected_id = $id;
        $this->name = $van->name;
        $this->updateMode = true;
    }

    public function update()
    {
        $this->validate([
            'selected_id' => 'required|numeric',
            'name' => 'required',
        ]);

        if ($this->selected_id) {
            $van = \App\Models\Van::find($this->selected_id);
            $van->update([
                'name' => $this->name,
            ]);

            $this->resetInput();
            $this->updateMode = false;
        }
    }
}
