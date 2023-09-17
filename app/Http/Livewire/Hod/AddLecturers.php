<?php

namespace App\Http\Livewire\Hod;

use App\Models\Department;
use App\Models\User;
use Livewire\Component;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;

class AddLecturers extends Component
{
    public $name, $email, $username, $password;
    public $title, $mobile, $staff_id;

    public $action, $param;


    protected function rules()
    {
        $rules = [
            'title' => 'required',
            'name' => 'required',
            'email' => 'nullable',
            'mobile' => 'required|numeric|digits:11',
            'staff_id' => 'nullable'
        ];

        if ($this->action == 'add')
            $rules['username'] = 'required|unique:users,username|regex:/^\S*$/u';

        return $rules;
    }

    public function mount($action, $param = '')
    {
        if (!in_array($action, ['add', 'update']))
            return redirect()->route('dashboard');

        $this->action = $action;
        if ($param) {
            $this->param = $param;
            $lecturer = User::whereEmail(base64_decode($param))->first();

            if (!$lecturer)
                return redirect()->route('dashboard');

            $this->name = $lecturer->name;
            $this->email = $lecturer->email;
            $this->mobile = $lecturer->mobile;
            $this->staff_id = $lecturer->staff_id;
            $this->title = $lecturer->title;
        }else{
            $this->name = '';
            $this->email = '';
            $this->mobile = '';
            $this->staff_id = '';
            $this->title = '';
        }
    }

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    public function submit()
    {
        $data = $this->validate();

        if ($this->action == 'add') {
            $data['password'] = Hash::make($this->username);
            $department = Department::find(auth()->user()->department_id);
            $data['department_id'] = $department->departments_id;
            $data['faculty_id'] = $department->fac_id;
            $roleName = 'lecturer';

            if(str_contains($this->username, '@') && str_contains($this->username, '.'))
            return session()->flash('error_toast', 'Invalid username supplied!'); 

            if(User::whereUsername($this->username)->count())
            return session()->flash('error_toast', 'Username already exist!'); 

            $data['name'] = ucwords($this->name);
            

            if (UserService::createUser($data, $roleName)) {
                $this->reset();
                return session()->flash('success_alert', 'Lecturer has been added');
            }

            return session()->flash('error_toast', 'Unable to add Lecturer');
        }

        $lecturer = User::whereEmail(base64_decode($this->param))->first();

        if ($lecturer->update($data))
            return session()->flash('success_alert', 'Lecturer account updated successfully!');

        return session()->flash('error_toast', 'An error occured while updating that lecturer\'s account!');
    }

    public function render()
    {
        return view('livewire.hod.add-lecturers');
    }
}
