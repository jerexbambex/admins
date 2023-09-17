<?php

namespace App\Http\Livewire\Dr;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\User;
use App\Services\UserService;
use Livewire\Component;

class AllHodsViewComponent extends Component
{
    public $faculty_id, $department_id;

    public function mount()
    {
        $this->faculty_id = '';
        $this->department_id = '';
    }

    public function updated($props)
    {
        if($props == 'faculty_id') $this->department_id = '';
    }

    public function resetPassword(User $user, UserService $userService)
    {
        if($user) $userService->resetPassword($user->id);
        return session()->flash('success_toast', 'Password reset successful!');
    }

    public function render()
    {
        $userService = new UserService();
        $faculties = Faculty::all();
        $departments = [];
        if($this->faculty_id) $departments = Department::whereFacId($this->faculty_id)->get();
        $hods = $userService->fetchUsers('hod');
        if($this->faculty_id) {
            $hods = $userService->fetchUsers('hod', NULL, $this->faculty_id);
        }
        if($this->department_id) $hods = $userService->fetchUsers('hod', $this->department_id, $this->faculty_id);
        $hods = $hods->paginate(PAGINATE_SIZE);

        return view('livewire.dr.all-hods-view-component', compact('hods', 'faculties', 'departments'));
    }
}
