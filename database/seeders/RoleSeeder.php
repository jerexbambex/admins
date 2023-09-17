<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::upsert([
            ['id' => 1, 'name' => 'rector'],
            ['id' => 2, 'name' => 'hod'],
            ['id' => 3, 'name' => 'lecturer'],
            ['id' => 4, 'name' => 'dr'],
            ['id' => 5, 'name' => 'bursary'],
            ['id' => 6, 'name' => 'admission'],
        ], ['id'], ['name']);
    }
}

