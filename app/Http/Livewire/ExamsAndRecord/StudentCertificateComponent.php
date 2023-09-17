<?php

namespace App\Http\Livewire\ExamsAndRecord;

use App\Models\BosLog;
use Livewire\Component;

class StudentCertificateComponent extends Component
{
    public $bos_number;

    function mount()
    {
        $this->bos_number = '';
    }

    function getBosLogsProperty()
    {
        return BosLog::whereBosNumber($this->bos_number)->get();
    }

    public function render()
    {
        return view('livewire.exams-and-record.student-certificate-component');
    }
}
