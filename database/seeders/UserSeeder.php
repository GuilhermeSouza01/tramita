<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Roles

        $admin = Role::firstOrCreate(['name' => 'admin']);
        $approver = Role::firstOrCreate(['name' => 'approver']);
        $requester = Role::firstOrCreate(['name' => 'requester']);

        $permissions = [
            'requests.view_own',
            'requests.view_all',
            'requests.create',
            'requests.cancel_own',
            'requests.approve',
            'request_types.manage',
            'departments.manage',
            'users.manage',
            'settings.manage',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Assign permissions to roles

        $admin->syncPermissions($permissions);

        $approver->syncPermissions([
            'requests.view_all',
            'requests.approve',
        ]);

        $requester->syncPermissions([
            'requests.view_own',
            'requests.create',
            'requests.cancel_own',
        ]);

        $departments = Department::all();

        $adminUser = User::create([
            'name' => 'Admin Tramita',
            'email' => 'admin@tramita.com.br',
            'password' => Hash::make('password'),
            'department_id' => $departments->firstWhere('name', 'Tecnologia')->id,
            'phone' => '(31) 99999-0001',
            'is_active' => true,
        ]);
        $adminUser->assignRole($admin);

        // Seed approvers

        $aprovadores = [
            [
                'name' => 'Carlos Mendes',
                'email' => 'carlos.mendes@tramita.com.br',
                'department_id' => $departments->firstWhere('name', 'Financeiro')->id,
                'phone' => '(31) 99999-0002',
            ],
            [
                'name'          => 'Fernanda Lima',
                'email'         => 'fernanda.lima@tramita.com.br',
                'department_id' => $departments->firstWhere('name', 'Recursos Humanos')->id,
                'phone'         => '(31) 99999-0003',
            ],
            [
                'name'          => 'Ricardo Souza',
                'email'         => 'ricardo.souza@tramita.com.br',
                'department_id' => $departments->firstWhere('name', 'Operações')->id,
                'phone'         => '(31) 99999-0004',
            ],
        ];

        foreach ($aprovadores as $data) {
            $user = User::create(array_merge($data, [
                'password'  => Hash::make('password'),
                'is_active' => true,
            ]));
            $user->assignRole($approver);
        }

        // Seed requesters

        $solicitantes = [
            [
                'name'          => 'Ana Paula Costa',
                'email'         => 'ana.paula@tramita.com.br',
                'department_id' => $departments->firstWhere('name', 'Comercial')->id,
                'phone'         => '(31) 99999-0005',
            ],
            [
                'name'          => 'Bruno Oliveira',
                'email'         => 'bruno.oliveira@tramita.com.br',
                'department_id' => $departments->firstWhere('name', 'Comercial')->id,
                'phone'         => '(31) 99999-0006',
            ],
            [
                'name'          => 'Juliana Ferreira',
                'email'         => 'juliana.ferreira@tramita.com.br',
                'department_id' => $departments->firstWhere('name', 'Tecnologia')->id,
                'phone'         => '(31) 99999-0007',
            ],
            [
                'name'          => 'Marcos Alves',
                'email'         => 'marcos.alves@tramita.com.br',
                'department_id' => $departments->firstWhere('name', 'Operações')->id,
                'phone'         => '(31) 99999-0008',
            ],
            [
                'name'          => 'Patricia Nunes',
                'email'         => 'patricia.nunes@tramita.com.br',
                'department_id' => $departments->firstWhere('name', 'Financeiro')->id,
                'phone'         => '(31) 99999-0009',
            ],
        ];

        foreach ($solicitantes as $data) {
            $user = User::create(array_merge($data, [
                'password'  => Hash::make('password'),
                'is_active' => true,
            ]));
            $user->assignRole($requester);
        }
    }
}
