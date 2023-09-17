<?php

namespace App\Http\Livewire\Admission;

use App\Models\Institution;
use Livewire\Component;
use Livewire\WithPagination;

class InstitutionsComponent extends Component
{
    public $institution_name, $search;

    protected $rules = [
        'institution_name'  =>  'required|string|unique:polytechnics,pname'
    ];

    function mount()
    {
        $this->institution_name = '';
        $this->search = '';
    }

    function updated($field)
    {
        $this->validateOnly($field);
    }

    function submit()
    {
        $this->validate();
        try {
            Institution::create(['pname' => $this->institution_name]);
            session()->flash('success_toast', 'Institution added successfully!');
        } catch (\Throwable $th) {
            session()->flash('error_toast', 'An error occured!');
        }
    }

    use WithPagination;

    public function render()
    {
        $institutions = [];

        if (!$this->search) $institutions = Institution::select(['pname'])->paginate(10);
        else $institutions = Institution::select(['pname'])->where('pname', 'like', "%$this->search%")->paginate(10);

        return view('livewire.admission.institutions-component', compact('institutions'));
    }
}
