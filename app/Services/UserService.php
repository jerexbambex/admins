<?php

namespace App\Services;

use App\Models\Matcode;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;

class UserService
{

    static function createUser(array $data, String $roleName): bool
    {
        if (!$data['email'])
            $data['email'] = $data['username'] . "@polyibadan.edu.ng";
        $data['created_by'] = auth()->user()->id;
        if (User::where('email', $data['email'])->count() > 0) return false;
        $role = Role::where('name', $roleName)->first(['id']);
        if($role){
            $user = User::create($data);
            UserRole::create([
                'user_id'   =>  $user->id,
                'role_id'   =>  $role->id,
                'assigned_by'   =>  auth()->user()->id
            ]);
            return true;
        }
        return false;
    }

    static function enableUser($user)
    {
        $user->status = 1;
        return $user->save();
    }

    static function disableUser($user)
    {
        $user->status = 0;
        return $user->save();
    }


    static function fetchUsers($roleName, $departmentId = NULL, $facultyId = NULL)
    {
        $users = User::select(['users.*']);
        if ($departmentId && is_numeric($departmentId)) $users = $users->where('department_id', $departmentId);

        if ($facultyId && is_numeric($facultyId)) {
            $users = $users->join('departments', 'departments.departments_id', 'users.department_id');
            $users = $users->join('faculties', 'faculties.faculties_id', 'departments.fac_id');
            $users = $users->whereFacultiesId($facultyId);
        }

        $users = $users->join('user_roles', 'user_roles.user_id', 'users.id')
            ->where('user_roles.role_id', Role::whereName($roleName)->first()->id);

        return $users;
    }

    static function resetPassword($userId)
    {
        $user = User::find($userId);
        if ($user) $user->password = Hash::make($user->username);
        return $user->save();
    }

    static function matcode($opt_id = 0, $prog_id = 0, $prog_type_id = 0)
    {
        return Matcode::select(['mid', 'deptcode'])->whereDoId($opt_id)->whereProgId($prog_id)->whereProgtypeId($prog_type_id);
    }
}
