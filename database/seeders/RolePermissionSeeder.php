<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            'platform.dashboard', 'schools.view', 'schools.create', 'schools.edit', 'schools.delete',
            'users.view', 'users.create', 'users.edit', 'users.delete',
            'activity-log.view', 'login-history.view', 'system.settings',
            'dashboard.view',
            'academic-years.view', 'academic-years.create', 'academic-years.edit', 'academic-years.delete',
            'grade-levels.view', 'grade-levels.create', 'grade-levels.edit', 'grade-levels.delete',
            'majors.view', 'majors.create', 'majors.edit', 'majors.delete',
            'classes.view', 'classes.create', 'classes.edit', 'classes.delete', 'classes.view-own',
            'rooms.view', 'rooms.create', 'rooms.edit', 'rooms.delete',
            'lesson-hours.view', 'lesson-hours.create', 'lesson-hours.edit', 'lesson-hours.delete',
            'students.view', 'students.create', 'students.edit', 'students.delete', 'students.view-own-class', 'students.view-children',
            'teachers.view', 'teachers.create', 'teachers.edit', 'teachers.delete',
            'parents.view', 'parents.create', 'parents.edit', 'parents.delete',
            'subjects.view', 'subjects.create', 'subjects.edit', 'subjects.delete',
            'schedules.view', 'schedules.create', 'schedules.edit', 'schedules.delete', 'schedules.view-own',
            'attendances.view', 'attendances.create', 'attendances.edit', 'attendances.view-own', 'attendances.view-children',
            'grades.view', 'grades.create', 'grades.edit', 'grades.view-own', 'grades.view-children',
            'report-cards.view', 'report-cards.create', 'report-cards.edit', 'report-cards.approve', 'report-cards.edit-notes',
            'announcements.view', 'announcements.create', 'announcements.edit', 'announcements.delete',
            'payments.view', 'payments.create', 'payments.edit', 'payments.view-own', 'payments.view-children',
            'elearning.view', 'elearning.create', 'elearning.edit', 'elearning.view-own',
            'reports.view', 'reports.export',
            'school-settings.view', 'school-settings.edit',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        $rolePermissions = [
            'super-admin' => Permission::all()->pluck('name')->toArray(),
            'admin-sekolah' => $this->adminPermissions(),
            'kepala-sekolah' => ['dashboard.view', 'reports.view', 'report-cards.view', 'report-cards.approve', 'announcements.view'],
            'wali-kelas' => ['dashboard.view', 'classes.view-own', 'attendances.create', 'attendances.view', 'grades.view', 'report-cards.edit-notes', 'students.view-own-class', 'announcements.view'],
            'guru' => ['dashboard.view', 'schedules.view-own', 'attendances.create', 'attendances.view', 'grades.create', 'grades.view', 'elearning.view', 'elearning.create', 'announcements.view'],
            'siswa' => ['dashboard.view', 'schedules.view-own', 'grades.view-own', 'attendances.view-own', 'announcements.view', 'elearning.view-own', 'payments.view-own'],
            'orang-tua' => ['dashboard.view', 'students.view-children', 'grades.view-children', 'attendances.view-children', 'announcements.view', 'payments.view-children'],
            'tata-usaha' => ['dashboard.view', 'students.view', 'students.create', 'teachers.view', 'parents.view', 'parents.create', 'payments.view', 'payments.create', 'reports.view', 'reports.export', 'announcements.view'],
        ];

        foreach ($rolePermissions as $roleName => $perms) {
            $role = Role::firstOrCreate(['name' => $roleName, 'guard_name' => 'web']);
            $role->syncPermissions($perms);
        }
    }

    private function adminPermissions(): array
    {
        return array_filter(Permission::pluck('name')->toArray(), fn ($p) => ! str_starts_with($p, 'platform.') && $p !== 'system.settings');
    }
}
