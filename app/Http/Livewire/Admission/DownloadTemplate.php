<?php

namespace App\Http\Livewire\Admission;

use App\Models\AppCurrentSession;
use App\Services\AdmissionsService;
use Livewire\Component;
use Livewire\WithPagination;

class DownloadTemplate extends Component
{
    public $prog_id = 0, $prog_type_id = 0, $adm_status = 0;

    public $pagesize = 10, $user_prog_type, $session_year;

    public $search = '';

    public function mount()
    {
        $this->user_prog_type = auth()->user()->prog_type_id;
        $this->session_year = session()->get('app_session');
    }

    use WithPagination;

    public function download()
    {
        try {
            return redirect()->route('download_admission_template_data', [
                'session_year' => $this->session_year,
                'prog_id' => $this->prog_id,
                'prog_type_id' => $this->user_prog_type ? $this->user_prog_type : $this->prog_type_id,
                'search' => base64_encode($this->search)
            ]);
        } catch (\Throwable $th) {
            return session()->flash('error_toast', 'An error occured');
        }
    }

    public function render()
    {
        $admissionService = new AdmissionsService;
        $prog_type = $this->user_prog_type ? $this->user_prog_type : $this->prog_type_id;
        $applicants = $admissionService->admissionTemplateData($this->session_year, $this->prog_id, $prog_type, base64_encode($this->search))
            ->paginate($this->pagesize);
        $sessions = AppCurrentSession::select(['cs_session'])->groupBy('cs_session')->get();
        return view('livewire.admission.download-template', compact('applicants', 'sessions'));
    }
}
