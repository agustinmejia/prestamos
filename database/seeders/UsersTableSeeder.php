<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use TCG\Voyager\Models\Role;
use TCG\Voyager\Models\User;

class UsersTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        if (User::count() == 0) {
            $role = Role::where('name', 'admin')->firstOrFail();

            User::create([
                'name'           => 'Admin',
                'email'          => 'admin@admin.com',
                'password'       => bcrypt('Code1997Soft'),
                'remember_token' => Str::random(60),
                'role_id'        => $role->id,
            ]);
            User::create([
                'name'           => 'Gerente',
                'email'          => 'gerente@admin.com',
                'password'       => bcrypt('12345678.,*'),
                'remember_token' => Str::random(60),
                'role_id'        => 2,
            ]);
        }
    }
}
