<?php

namespace App\Http\Livewire\Hod;

use Livewire\Component;

class ResultDatesComponent extends Component
{
    public $session, $date_type, $date_from, $date_to;

    protected $rules = [
        'session' =>  'required|string',
        'date_type' =>  'required|string',
        'date_from' =>  'required|date',
        'date_to' =>  'required|date',
    ];

    public function mount()
    {
        $this->date_type = $this->date_from = $this->date_to = '';
    }

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    public function submit()
    {
        $data = $this->validate();
    }

    public function render()
    {
        return view('livewire.hod.result-dates-component');
    }
}
