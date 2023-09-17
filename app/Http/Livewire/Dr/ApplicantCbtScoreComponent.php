<?php

namespace App\Http\Livewire\Dr;

use App\Exports\ApplicantCBTScoreExport;
use App\Imports\ApplicantCBTScoreImport;
use Livewire\Component;
use Livewire\WithFileUploads;
use Maatwebsite\Excel\Facades\Excel;

class ApplicantCbtScoreComponent extends Component
{
    public $file;

    protected $rules = [
        'file'    =>  'required|file|mimes:xlsx,xls,csv',
    ];

    public function update($prop)
    {
        $this->validateOnly($prop);
    }

    // public function upload()
    // {
    //     $this->validate();
    //     Excel::import(new ApplicantCBTScoreImport(), $this->file->path());
    // }

    function download_template()
    {
        try {
            return Excel::download(new ApplicantCBTScoreExport(), 'applicants_cbt_score_template.xlsx');
        } catch (\Throwable $th) {
            return session()->flash('error_toast', 'Unable to download');
        }
    }

    use WithFileUploads;

    public function render()
    {
        return view('livewire.dr.applicant-cbt-score-component');
    }
}
