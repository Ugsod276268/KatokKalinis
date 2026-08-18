<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Driver
        $driverId = DB::table('users')->updateOrInsert(
            ['email' => 'driver@katokkalinis.com'],
            [
                'name' => 'John Driver',
                'password' => Hash::make('Driver@123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $driver = DB::table('users')
            ->where('email', 'driver@katokkalinis.com')
            ->first();

        $driverRole = DB::table('roles')
            ->where('name', 'driver')
            ->first();

        if ($driver && $driverRole) {
            DB::table('user_roles')->updateOrInsert(
                [
                    'user_id' => $driver->id,
                    'role_id' => $driverRole->id,
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        // Inspector
        DB::table('users')->updateOrInsert(
            ['email' => 'inspector@katokkalinis.com'],
            [
                'name' => 'John Inspector',
                'password' => Hash::make('Inspector@123'),
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        $inspector = DB::table('users')
            ->where('email', 'inspector@katokkalinis.com')
            ->first();

        $inspectorRole = DB::table('roles')
            ->where('name', 'inspector')
            ->first();

        if ($inspector && $inspectorRole) {
            DB::table('user_roles')->updateOrInsert(
                [
                    'user_id' => $inspector->id,
                    'role_id' => $inspectorRole->id,
                ],
                [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
