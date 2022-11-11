<?php

namespace Database\Seeders;

use App\Role;
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
        Role::firstOrCreate([
            'name' => 'Admin',
        ]);
        Role::firstOrCreate([
            'name' => 'User',
        ]);
    }
}
