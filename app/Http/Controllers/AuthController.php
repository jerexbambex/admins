<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\SchoolSession;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function userLogin(Request $request)
    {
        $data = $this->validate($request, [
            'login_id'  =>  'required|string',
            'password'  =>  'required|string',
            'role'      =>  'required|string'
        ], [
            'login_id.required' =>  'Please enter your email or phone'
        ]);

        $role = Role::whereName($data['role'])->first();
        
        $user = User::where(function ($query) use ($data) {
            $query->where('email', $data['login_id'])
                ->orWhere('username', $data['login_id']);
        })->first();

        if (!$user) return response()->json(['status' => 'error', 'message' => 'User does not exist!']);
        if (!$user->status) return response()->json(['status' => 'error', 'message' => 'User is restricted!']);
        if(!$user->hasRole($data['role'])) return response()->json(['status' => 'error', 'message' => 'User does not exist on this platform!']);

        if (!Hash::check($data['password'], $user->password))
            return response()->json(['status' => 'error', 'message' => 'Bad credentials']);

        return $this->onSuccessfulLogin($user, $role);
    }

    public function onSuccessfulLogin($user, $role)
    {
        $token = $user->createToken('Bearer')->plainTextToken;

        $response = [
            'status'    =>  'success',
            'message'   =>  'Login successful!',
            'data'      =>  [
                'user'              =>  $user,
                'token'             =>  $token,
                'current_session'   => SchoolSession::latest()->first(),
                'adm_session'       =>  DB::table('app_current_session')->first()->cs_session,
                'role'              =>  $role
            ]
        ];

        return response()->json($response);
    }
    
    public function logOut()
    {
        $user = auth()->user();
        if($user){
            // $user->tokens()->delete();
            $user->currentAccessToken()->delete();

            return response()->json([
                'status'    =>  'success',
                'message'   =>  'Logged Out'
            ]);
        }
        return response()->json([
            'status'    =>  'error',
            'message'   =>  'User not logged in'
        ]);
    }
}
