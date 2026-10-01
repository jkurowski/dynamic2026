<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionPageSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'page-list',
            'page-create',
            'page-edit',
            'page-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}

