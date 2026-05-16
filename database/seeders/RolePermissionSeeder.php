<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['admin', 'captain', 'kagawad', 'secretary', 'tanod', 'resident'];

        $permissions = [
            'manage_users', 'manage_roles', 'manage_settings',
            'approve_residents', 'verify_residents',
            'manage_documents', 'process_documents', 'release_documents',
            'manage_complaints', 'investigate_complaints', 'mediate_complaints', 'resolve_complaints',
            'manage_blotter', 'create_blotter',
            'manage_skills_services', 'approve_skills_services',
            'manage_businesses', 'approve_businesses', 'inspect_businesses',
            'view_analytics', 'export_reports',
            'manage_announcements',
            'view_activity_logs', 'view_audit_logs',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name, 'guard_name' => 'web']);
        }

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        Role::findByName('admin')->syncPermissions(Permission::all());

        Role::findByName('captain')->syncPermissions([
            'approve_residents', 'approve_skills_services', 'approve_businesses',
            'manage_complaints', 'resolve_complaints',
            'view_analytics', 'export_reports', 'manage_announcements',
            'view_activity_logs', 'view_audit_logs',
        ]);

        Role::findByName('kagawad')->syncPermissions([
            'approve_residents', 'approve_skills_services', 'approve_businesses',
            'view_analytics', 'export_reports', 'manage_announcements',
        ]);

        Role::findByName('secretary')->syncPermissions([
            'verify_residents', 'approve_skills_services', 'approve_businesses',
            'process_documents', 'release_documents', 'manage_documents',
            'mediate_complaints',
            'manage_announcements',
        ]);

        Role::findByName('tanod')->syncPermissions([
            'manage_blotter', 'create_blotter',
            'investigate_complaints', 'mediate_complaints',
        ]);

        Role::findByName('resident')->syncPermissions([]);
    }
}
