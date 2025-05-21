<?php

namespace Database\Seeders; // ✅ مهم جداً حتى يتعرف عليه Laravel

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run()
    {
        // إنشاء الأدوار
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'withdraw_user']);
        Role::create(['name' => 'credit_user']);
        Role::create(['name' => 'report_user']);

        // إنشاء الصلاحيات
        Permission::create(['name' => 'manage withdrawals']);
        Permission::create(['name' => 'manage credits']);
        Permission::create(['name' => 'view reports']);
        Permission::create(['name' => 'full access']);

        // ربط الصلاحيات بالأدوار
        Role::findByName('admin')->givePermissionTo(['full access']);
        Role::findByName('withdraw_user')->givePermissionTo(['manage withdrawals']);
        Role::findByName('credit_user')->givePermissionTo(['manage credits']);
        Role::findByName('report_user')->givePermissionTo(['view reports']);
    }
}
