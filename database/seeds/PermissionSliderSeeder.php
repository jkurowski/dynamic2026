<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSliderSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'slider-list',
            'slider-create',
            'slider-edit',
            'slider-delete',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
