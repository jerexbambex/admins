<?php

namespace App\Http\Livewire;

use App\Models\SchoolSession;
use Livewire\Component;

class SessionChangeComponent extends Component
{
    public $cur_session, $new_session;

    public function mount()
    {
        $this->cur_session = session()->get('app_session');
        $this->new_session = "";
    }

    public function change_session()
    {
        if (!$this->new_session)
            return session()->flash('error_toast', 'New Session field cannot be empty!');

        if ($this->cur_session == $this->new_session)
            return session()->flash('error_toast', 'New and Current Session fields cannot be the same!');

        session()->remove('app_session');
        session()->remove('sch_session');
        session()->put('app_session', $this->new_session);
        session()->put('sch_session', sprintf('%s/%s', $this->new_session, $this->new_session + 1));

        session()->flash('success_alert', 'Session changed successfully!');
        return $this->mount();
    }

    public function render()
    {
        $sch_sessions = SchoolSession::orderBy('year', 'desc')->get(['year', 'session']);
        return view('livewire.session-change-component', compact('sch_sessions'));
    }
}
