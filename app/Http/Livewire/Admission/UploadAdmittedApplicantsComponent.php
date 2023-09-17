<?php

namespace App\Http\Livewire\Admission;

use App\Imports\AdmittedApplicantsImport;
use App\Models\AppCurrentSession;
use App\Models\SchoolSession;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class UploadAdmittedApplicantsComponent extends Component
{
    public $file, $adm_year;
    
    protected $rules = [
        'adm_year'    =>  'required',
        'file'    =>  'required|file|mimes:xlsx,xls,csv',
    ];

    public function mount()
    {
        $this->adm_year = AppCurrentSession::select(['cs_session'])->whereStatus('current')->groupBy('cs_session')->first(['cs_session'])->cs_session;
    }

    public function updated($prop)
    {
        $this->validateOnly($prop);
    }

    // public function upload()
    // {
    //     $this->validate();
    //     // dd($this->file);
    //     Excel::import(new AdmittedApplicantsImport($this->adm_year), $this->file->path());
    // }

    use WithFileUploads;

    public function render()
    {
        $sessions = AppCurrentSession::select(['cs_session'])->groupBy('cs_session')->get();
        return view('livewire.admission.upload-admitted-applicants-component', compact('sessions'));
    }
}
