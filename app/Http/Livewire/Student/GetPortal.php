<?php

namespace App\Http\Livewire\Student;

use App\Models\Portal;
use Livewire\Component;
use Livewire\WithPagination;

class GetPortal extends Component
{
    public $pagesize = 10;

    public $search = '';

    public function deleteDuplicate(Portal $portal)
    {
        if($portal->delete()) return session()->flash('success_toast', 'Data deleted successfully!');
        return session()->flash('error_toast', 'Unable to perform that action!');
    }

    use WithPagination;

    public function render()
    {
        $portals = Portal::where('appno', 'like', '%' . $this->search . '%')
            ->paginate($this->pagesize);
        return view('livewire.student.get-portal', ['portals'=> $portals]);
    }
}
