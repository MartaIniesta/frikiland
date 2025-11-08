<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // 1️⃣ Crear Roles
        $roles = ['user', 'seller', 'admin'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // 2️⃣ Crear Permisos
        $permissions = [
            'crear_post',
            'editar_post',
            'borrar_post',
            'crear_producto',
            'editar_producto',
            'ver_ventas',
            'gestionar_usuarios',
            'asignar_roles',
        ];
        foreach ($permissions as $perm) {
            Permission::firstOrCreate(['name' => $perm]);
        }

        // 3️⃣ Asignar permisos a roles
        Role::findByName('user')->givePermissionTo(['crear_post', 'editar_post']);
        Role::findByName('seller')->givePermissionTo(['crear_post','editar_post','crear_producto','editar_producto','ver_ventas']);
        Role::findByName('admin')->givePermissionTo(Permission::all());

        // 4️⃣ Crear un usuario de prueba
        $user = User::factory()->create([
            'name' => 'David',
            'username' => 'davidfriki',
            'email' => 'redsocial@example.com',
            'password' => Hash::make('asdf'),
            'email_verified_at' => now(),
            'avatar' => null,
            'bio' => 'Usuario de prueba',
        ]);

        $user->assignRole('user');
    }
}