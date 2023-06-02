<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            ['username' => 'user1t1', 'password' => Hash::make('12345678'), 'tenant_id' => 1, 'role_id' => 3],
            ['username' => 'user2t1', 'password' => Hash::make('12345678'), 'tenant_id' => 1, 'role_id' => 3],
            ['username' => 'user1t2', 'password' => Hash::make('12345678'), 'tenant_id' => 2, 'role_id' => 3],
            ['username' => 'user2t2', 'password' => Hash::make('12345678'), 'tenant_id' => 2, 'role_id' => 3],
            ['username' => 'admin', 'password' => Hash::make('12345678'), 'tenant_id' => 1, 'role_id' => 1],
            ['username' => 'usertenant1', 'password' => Hash::make('12345678'), 'tenant_id' => 1, 'role_id' => 2],
            ['username' => 'usertenant2', 'password' => Hash::make('12345678'), 'tenant_id' => 2, 'role_id' => 2],
        ]);
    }
}
