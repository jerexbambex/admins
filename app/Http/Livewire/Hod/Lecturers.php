<?php

namespace App\Http\Livewire\Hod;

use Livewire\Component;
use App\Models\User;
use App\Services\UserService;
use Livewire\WithPagination;

class Lecturers extends Component
{
    public function disableUser(User $user){
        if(UserService::disableUser($user))
        return session()->flash('success_alert', 'Lecturer has been disabled!');

        return session()->flash('error_toast', 'Unable to perform action!');
    }

    public function enableUser(User $user){
        if(UserService::enableUser($user))
        return session()->flash('success_alert', 'Lecturer has been enabled!');

        return session()->flash('error_toast', 'Unable to perform action!');
    }

    public function resetPassword($id)
    {
        if(UserService::resetPassword($id))
        return session()->flash('success_toast', 'Lecturer password reset successful!');

        return session()->flash('error_toast', 'Unable to perform action!');
    }

    use WithPagination;

    public function render()
    {
        // dd(auth()->user()->department_id);
        $lecturers = UserService::fetchUsers('lecturer', auth()->user()->department_id)->paginate(PAGINATE_SIZE);
        return view('livewire.hod.lecturers',  ['lecturers'=>$lecturers]);
    }
}
