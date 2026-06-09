<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        // Cache clear
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 1. all permissions 
        $permissions = [
            'view factories',    'create factories',    'edit factories',    'delete factories',
            'view machines',     'create machines',     'edit machines',     'delete machines',
            'view productions',  'create productions',  'edit productions',  'delete productions',
            'view employees',    'create employees',    'edit employees',    'delete employees',
            'view attendance',   'create attendance',   'edit attendance',   'delete attendance',
            'view users',        'create users',        'edit users',        'delete users',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name'       => $permission,
                'guard_name' => 'web',
            ]);
        }

        // 2. Roles
        $owner    = Role::firstOrCreate(['name' => 'owner',    'guard_name' => 'web']);
        $manager  = Role::firstOrCreate(['name' => 'manager',  'guard_name' => 'web']);
        $employee = Role::firstOrCreate(['name' => 'employee', 'guard_name' => 'web']);

        // 3. Owner - assume full permissions
        $owner->syncPermissions(Permission::all());

        // 4. Manager — limited permissions
        $manager->syncPermissions([
            'view factories',
            'view machines',     'create machines',    'edit machines',
            'view productions',  'create productions', 'edit productions',
            'view employees',
            'view attendance',   'create attendance',  'edit attendance',
        ]);

        // 5. Employee — very limited permissions
       // Employee — update karo
        $employee->syncPermissions([
            // Production
            'view productions', 'create productions',
            'view machines',
            'view attendance',
            'create attendance',
            'scan qr',
        ]);

        // 6. Assign owner role to specific user
        $ownerUser = User::where('email', 'own@gmail.com')->first();
        if ($ownerUser) {
            $ownerUser->syncRoles(['owner']);
        }
    }
}