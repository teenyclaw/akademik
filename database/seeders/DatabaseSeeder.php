<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolePermissionSeeder::class,
            EducationLevelSeeder::class,
        ]);

        $superAdmin = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@akademik.test',
            'password' => Hash::make('password'),
            'is_super_admin' => true,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $superAdmin->assignRole('super-admin');

        $this->call(DemoSchoolSeeder::class);
    }
}
