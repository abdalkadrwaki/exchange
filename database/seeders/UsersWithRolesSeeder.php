<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UsersWithRolesSeeder extends Seeder
{
    public function run()
    {
        // تأكد أن الأدوار موجودة
        $roles = ['admin', 'withdraw_user', 'credit_user', 'report_user'];

        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // مستخدم أدمن
        $admin = User::create([
            'name' => 'مدير النظام',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
        ]);
        $admin->assignRole('admin');

        // مستخدم صرف
        $withdraw = User::create([
            'name' => 'موظف صرف',
            'email' => 'withdraw@example.com',
            'password' => Hash::make('password'),
        ]);
        $withdraw->assignRole('withdraw_user');

        // مستخدم اتمادات
        $credit = User::create([
            'name' => 'موظف اتمادات',
            'email' => 'credit@example.com',
            'password' => Hash::make('password'),
        ]);
        $credit->assignRole('credit_user');

        // مستخدم كشف
        $report = User::create([
            'name' => 'موظف كشف',
            'email' => 'report@example.com',
            'password' => Hash::make('password'),
        ]);
        $report->assignRole('report_user');
    }
}
