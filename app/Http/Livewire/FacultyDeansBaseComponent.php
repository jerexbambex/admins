<?php

namespace App\Http\Livewire;

use App\Models\Faculty;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class FacultyDeansBaseComponent extends Component
{
    public $title, $name, $username, $email, $staff_id, $mobile, $faculty;
    public $filter_status;

    public $action, $dean_id;

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
        ];
        if ($this->action <> 'create') {
            unset($rules['username']);
            unset($rules['faculty']);
            unset($rules['username']);
        }
        return $rules;
    }

    public function mount()
    {
        $this->action = "create";
        $this->dean_id = 0;
        $this->filter_status = "1";
        $this->title = "";
        $this->name = "";
        $this->username = "";
        $this->email = "";
        $this->staff_id = "";
        $this->mobile = "";
        $this->faculty = "";
    }

    public function updated($field)
    {
        $this->validateOnly($field);
    }

    public function enableCreate()
    {
        $this->reset();
        $this->mount();
    }

    public function enableUpdate(User $user)
    {
        $this->dean_id = $user->id;
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
            $data['department_id'] = 0;
            $data['faculty_id'] = $data['faculty'];
            unset($data['faculty']);
            $roleName = 'faculty-dean';

            if (str_contains($this->username, '@') && str_contains($this->username, '.'))
                return session()->flash('error_toast', 'Invalid username supplied!');

            if (User::whereUsername($this->username)->count())
                return session()->flash('error_toast', 'Username already exist!');

            $data['name'] = ucwords($this->name);

            DB::table('users')->join('user_roles', 'user_roles.user_id', 'users.id')
                ->whereRaw("user_roles.role_id in (SELECT id from roles where name = 'faculty-dean')")
                ->whereFacultyId($this->faculty)
                ->update(['status' => 0]);

            if (UserService::createUser($data, $roleName)) {
                $this->mount();
                return session()->flash('success_alert', 'Faculty Dean has been created');
            }

            return session()->flash('error_toast', 'Unable to create Faculty Dean');
        }

        if (!$this->dean_id)
            return session()->flash('error_toast', 'An error occured while updating that Faculty Dean\'s account!');
        $dean = User::find($this->dean_id);

        if ($dean->update($data)) {
            $this->enableCreate();
            return session()->flash('success_alert', 'Faculty Dean\'s account updated successfully!');
        }

        return session()->flash('error_toast', 'An error occured while updating that Faculty Dean\'s account!');
    }

    public function blockUser(User $user)
    {
        if ($user->update(['status' => 0]))
            return session()->flash('success_alert', 'Faculty Dean\'s account blocked!');

        return session()->flash('error_toast', 'Unable to perform that action!');
    }

    public function resetPassword(User $user)
    {
        $user->password = Hash::make($user->username);
        return session()->flash('success_alert', 'Faculty Dean\'s password reset successful!');
    }

    public function enableUser(User $user)
    {
        if ($user->update(['status' => 1]))
            return session()->flash('success_alert', 'Faculty Dean\'s account activated!');

        return session()->flash('error_toast', 'Unable to perform that action!');
    }

    public function render()
    {
        $faculties = Faculty::all(['faculties_id', 'faculties_name']);

        $deans = User::selectRaw('users.id, users.title, users.name, users.staff_id, users.faculty_id, users.email, users.username, users.mobile, faculties.faculties_name as faculty_name')
            ->join('user_roles', 'user_roles.user_id', 'users.id')
            ->join('faculties', 'faculties.faculties_id', 'users.faculty_id');
        $deans->whereRaw("user_roles.role_id in (SELECT id from roles where name = 'faculty-dean')")->whereStatus($this->filter_status);

        $deans = $deans->paginate(PAGINATE_SIZE);

        return view('livewire.faculty-deans-base-component', compact('faculties', 'deans'));
    }
}
