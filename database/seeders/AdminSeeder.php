<?php

namespace Database\Seeders;

use App\Models\Hod;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //Rector
        User::upsert([
            [
                'name' => 'Rector',
                'username' => 'rector', 
                'email' => 'rector@polyibadan.edu.ng', 
                'password' => bcrypt('pa55w0rd'),
                'department_id' => 0
            ]
        ], ['email'], []);
        $user = User::where('email', 'rector@polyibadan.edu.ng')->first();
        $user->roles()->attach(Role::where('name', 'rector')->first());

        //Bursary
        User::upsert([
            [
                'name' => 'Bursary',
                'username' => 'bursary', 
                'email' => 'bursary@polyibadan.edu.ng', 
                'password' => bcrypt('pa55w0rd'),
                'department_id' => 0
            ]
        ], ['email'], []);
        $user = User::where('email', 'bursary@polyibadan.edu.ng')->first();
        $user->roles()->attach(Role::where('name', 'bursary')->first());
        
        //Deputy Registrar
        User::upsert([
            [
                'name' => 'Deputy-Registrar',
                'username' => 'salami', 
                'email' => 'salami@polyibadan.edu.ng', 
                'password' => bcrypt('salami'),
                'department_id' => 0
            ]
        ], ['email'], []);
        $user = User::where('email', 'salami@polyibadan.edu.ng')->first();
        $user->roles()->attach(Role::where('name', 'dr')->first());
        
        //Deputy Rector
        User::upsert([
            [
                'name' => 'Deputy-Rector',
                'username' => 'drector', 
                'email' => 'drector@polyibadan.edu.ng', 
                'password' => bcrypt('pa55w0rd'),
                'department_id' => 0
            ]
        ], ['email'], []);
        $user = User::where('email', 'drector@polyibadan.edu.ng')->first();
        $user->roles()->attach(Role::where('name', 'rector')->first());

        //Admission
        User::upsert([
            [
                'name' => 'Admission',
                'username' => 'admission', 
                'email' => 'admission@polyibadan.edu.ng', 
                'password' => bcrypt('22222222'),
                'department_id' => 0
            ]
        ], ['email'], []);
        $user = User::where('email', 'admission@polyibadan.edu.ng')->first();
        $user->roles()->attach(Role::where('name', 'admission')->first());



        //HODs
        $hods = Hod::all();

        foreach($hods as $hod){
            User::upsert([
                [
                    'name' => "$hod->surname $hod->firstname", 
                    'username' => "$hod->username",
                    'email' => "$hod->username@polyibadan.edu.ng", 
                    'password' => bcrypt("$hod->username"),
                    'department_id' => $hod->department_id
                ]
            ], ['email'], []);
            $user = User::where('email', "$hod->username@polyibadan.edu.ng")->first();
            $user->roles()->attach(Role::where('name', 'hod')->first());
        }
    }
}

