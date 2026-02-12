<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Limpiar caché de permisos
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // Definición de permisos (por dominio)
        $permissions = [
            // Posts
            'post.create',
            'post.update',
            'post.delete',

            'favorite.post',
            'shared.post',

            // Comentarios
            'comment.create',
            'comment.update',
            'comment.delete',

            // Productos
            'product.create',
            'product.update',
            'product.delete',

            // Ventas
            'sales.view',

            // Usuarios / roles
            'users.manage',
            'roles.assign',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Definición de roles y sus permisos
        $roles = [
            'user' => [
                'post.create',
                'post.update',
                'post.delete',
                'comment.create',
                'comment.update',
                'comment.delete',
                'favorite.post',
                'shared.post',
            ],

            'seller' => [
                'post.create',
                'post.update',
                'product.create',
                'product.update',
                'sales.view',
            ],

            'admin' => Permission::all()->pluck('name')->toArray(),
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            $role->syncPermissions($rolePermissions);
        }
    }
}
