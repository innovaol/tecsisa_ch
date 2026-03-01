<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create Roles
        $adminRole = Role::create(['name' => 'Administrador']);
        $tecnicoRole = Role::create(['name' => 'Tecnico']);

        // Create basic permissions (we can expand this later)
        Permission::create(['name' => 'manage everything']);
        Permission::create(['name' => 'manage assignments']);

        // Assign permissions
        $adminRole->givePermissionTo('manage everything');
        $tecnicoRole->givePermissionTo('manage assignments');

        // Create an initial Admin user
        $adminUser = User::create([
            'name' => 'Admin Tecsisa',
            'email' => 'admin@tecsisa.com',
            'password' => Hash::make('password'),
        ]);

        $adminUser->assignRole($adminRole);

        // Create an initial Tecnico user for testing
        $tecnicoUser = User::create([
            'name' => 'Tecnico Prueba',
            'email' => 'tecnico@tecsisa.com',
            'password' => Hash::make('password'),
        ]);

        $tecnicoUser->assignRole($tecnicoRole);
    }
}
