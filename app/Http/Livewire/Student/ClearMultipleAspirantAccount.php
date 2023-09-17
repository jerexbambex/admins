<?php

namespace App\Http\Livewire\Student;

use App\Models\Portal;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class ClearMultipleAspirantAccount extends Component
{
    public $multiple, $mc_status;

    protected $rules = [
        'multiple'  =>  'required|numeric|min:2|max:100'
    ];

    protected $listeners = ['clearer' => 'clearer'];

    public function mount()
    {
        $this->multiple = 100;
        $this->mc_status = false;
    }

    public function clearDuplicates($form_number, $message = true)
    {
        $portal = Portal::whereAppno($form_number);
        $aspirant = $portal->first();
        if(!$aspirant) return session()->flash('error_toast', 'Aspirant not found!');
        // dd($portal->where('pid', '!=', $aspirant->pid)->count());
        Portal::whereAppno($form_number)->where('pid', '!=', $aspirant->pid)->delete();

        if($message) session()->flash('success_toast', 'Duplicates cleared!');
        return true;
    }

    public function clearer()
    {
        $portals = DB::table('portalaccess')
                    ->selectRaw('fullname, appno')
                    ->groupByRaw('appno, fullname')
                    ->havingRaw('count(*) > ?', [1])
                    ->take($this->multiple)->get();

        foreach($portals as $portal){
            $this->clearDuplicates($portal->appno, false);
        }

        session()->flash('success_toast', "Multiple Duplicates cleared: $this->multiple!");

        $this->mount();
    }

    public function multipleClear()
    {
        $this->validate();
        $this->mc_status = true;
        
        $this->emit('clearer');
    }

    public function render()
    {
        $portals = DB::table('portalaccess')
                    ->selectRaw('fullname, appno')
                    ->groupByRaw('appno, fullname')
                    ->havingRaw('count(*) > ?', [1])
                    ->paginate(PAGINATE_SIZE * 2);
        return view('livewire.student.clear-multiple-aspirant-account', compact('portals'));
    }
}
