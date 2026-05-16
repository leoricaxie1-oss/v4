<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $accounts = [
            ['admin@panipuan.gov.ph',     '09170000001', 'System',  'Administrator', 'admin'],
            ['captain@panipuan.gov.ph',   '09170000002', 'Juan',    'Dela Cruz',     'captain'],
            ['kagawad@panipuan.gov.ph',   '09170000003', 'Maria',   'Santos',        'kagawad'],
            ['secretary@panipuan.gov.ph', '09170000004', 'Ana',     'Reyes',         'secretary'],
            ['tanod@panipuan.gov.ph',     '09170000005', 'Pedro',   'Garcia',        'tanod'],
        ];

        foreach ($accounts as [$email, $phone, $first, $last, $role]) {
            $user = User::firstOrCreate(
                ['email' => $email],
                [
                    'first_name'       => $first,
                    'last_name'        => $last,
                    'phone'            => $phone,
                    'password'         => Hash::make('Panipone@2026'),
                    'email_verified_at'=> now(),
                    'account_status'   => 'approved',
                    'approved_by_secretary' => true,
                    'approved_by_kagawad'   => true,
                    'approved_by_captain'   => true,
                    'approved_by_secretary_at' => now(),
                    'approved_by_kagawad_at'   => now(),
                    'approved_by_captain_at'   => now(),
                ]
            );

            if (! $user->hasRole($role)) {
                $user->assignRole($role);
            }
        }
    }
}
