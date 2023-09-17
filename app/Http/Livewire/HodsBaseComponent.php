<?php

namespace App\Http\Livewire;

use App\Models\Department;
use App\Models\Faculty;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class HodsBaseComponent extends Component
{
    public $title, $name, $username, $email, $staff_id, $mobile, $faculty, $department;
    public $filter_faculty, $filter_department, $filter_status;

    public $action, $hod_id;

    protected function rules()
    {
        $rules = [
            'title' =>  'required',
            'name'  =>  'required',
            'username'  =>  'required|unique:users,username|regex:/^\S*$/u',
            'email' =>  'required',
            'staff_id'  =>  'nullable',
            'mobile'    =>  'required',
            'faculty'   =>  'required|numeric',
            'department'    =>  'required|numeric',
        ];
        if ($this->action <> 'create') {
            unset($rules['username']);
            unset($rules['faculty']);
            unset($rules['department']);
            unset($rules['username']);
        }
        return $rules;
    }

    public function mount()
    {
        $this->action = "create";
        $this->hod_id = 0;
        $this->filter_status = 1;
    }

    public function updated($field)
    {
        if ($field == 'faculty') $this->department = "";
        elseif ($field == 'filter_faculty') $this->filter_department = "";

        $this->validateOnly($field);
    }

    public function enableCreate()
    {
        $this->reset();
        $this->mount();
    }

    public function enableUpdate(User $user)
    {
        $this->hod_id = $user->id;
        $this->action = 'update';
        $this->title = $user->title;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->staff_id = $user->staff_id;
        $this->mobile = $user->mobile;
    }

    public function submit()
    {
        $data = $this->validate();

        if ($this->action == 'create') {
            $data['password'] = Hash::make($this->username);
            $data['department_id'] = $data['department'];
            $data['faculty_id'] = $data['faculty'];
            unset($data['department']);
            unset($data['faculty']);
            $roleName = 'hod';

            if (str_contains($this->username, '@') && str_contains($this->username, '.'))
                return session()->flash('error_toast', 'Invalid username supplied!');

            if (User::whereUsername($this->username)->count())
                return session()->flash('error_toast', 'Username already exist!');

            $data['name'] = ucwords($this->name);

            DB::table('users')->join('user_roles', 'user_roles.user_id', 'users.id')
                ->whereRaw("user_roles.role_id in (SELECT id from roles where name = 'hod')")
                ->whereDepartmentId($this->department)
                ->update(['status' => 0]);

            if (UserService::createUser($data, $roleName)) {
                $this->reset();
                return session()->flash('success_alert', 'H.O.D has been created');
            }

            return session()->flash('error_toast', 'Unable to create H.O.D');
        }

        if (!$this->hod_id)
            return session()->flash('error_toast', 'An error occured while updating that h.o.d\'s account!');
        $hod = User::find($this->hod_id);

        if ($hod->update($data)) {
            $this->enableCreate();
            return session()->flash('success_alert', 'H.O.D\'s account updated successfully!');
        }

        return session()->flash('error_toast', 'An error occured while updating that h.o.d\'s account!');
    }

    public function blockUser(User $user)
    {
        if ($user->update(['status' => 0]))
            return session()->flash('success_alert', 'H.O.D\'s account blocked!');

        return session()->flash('error_toast', 'Unable to perform that action!');
    }

    public function enableUser(User $user)
    {
        if ($user->update(['status' => 1]))
            return session()->flash('success_alert', 'H.O.D\'s account activated!');

        return session()->flash('error_toast', 'Unable to perform that action!');
    }

    public function render()
    {
        $departments = $filterdepartments = [];
        $faculties = Faculty::all(['faculties_id', 'faculties_name']);
        if ($this->faculty) $departments = Department::whereFacId($this->faculty)->get(['departments_id', 'departments_name']);
        if ($this->filter_faculty) $filterdepartments = Department::whereFacId($this->filter_faculty)->get(['departments_id', 'departments_name']);


        $hods = User::selectRaw('users.id, users.title, users.name, users.staff_id, users.department_id, users.email, users.mobile, departments.departments_name as department_name')
            ->join('user_roles', 'user_roles.user_id', 'users.id')
            ->join('departments', 'departments.departments_id', 'users.department_id');
        $hods->whereRaw("user_roles.role_id in (SELECT id from roles where name = 'hod')")->whereStatus($this->filter_status);
        if ($this->filter_department) $hods->whereDepartmentId($this->filter_department);
        elseif ($this->filter_faculty) $hods->whereRaw("department_id in (SELECT departments_id from departments where fac_id = $this->filter_faculty)");

        $hods = $hods->paginate(PAGINATE_SIZE);

        return view('livewire.hods-base-component', compact('faculties', 'departments', 'filterdepartments', 'hods'));
    }
}
