<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            ['name' => 'Financeiro',         'description' => 'Gestão financeira e contábil'],
            ['name' => 'Recursos Humanos',   'description' => 'Gestão de pessoas e benefícios'],
            ['name' => 'Tecnologia',         'description' => 'Infraestrutura e desenvolvimento'],
            ['name' => 'Comercial',          'description' => 'Vendas e relacionamento com clientes'],
            ['name' => 'Operações',          'description' => 'Processos e logística'],
        ];

       foreach ($departments as $data) {
            Department::create(array_merge($data, [
                'slug' => Str::slug($data['name']),
            ]));
        }
    }
}
