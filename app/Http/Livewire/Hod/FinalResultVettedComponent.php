<?php

namespace App\Http\Livewire\Hod;

use App\Models\BosLog;
use Livewire\Component;

class FinalResultVettedComponent extends Component
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
        return view('livewire.hod.final-result-vetted-component');
    }
}
