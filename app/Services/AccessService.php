<?php
namespace App\Services;

use Illuminate\Http\Request;

class AccessService {

    static function grantAccess() : bool
    {
        $user = request()->user();

        if($user->hasRole('rector')) return !password_verify('pa55w0rd', $user->password);
        
        return !password_verify($user->username, $user->password);    
    }

    static function loginStatus() : bool
    {
        return request()->user()->status;
    }
}