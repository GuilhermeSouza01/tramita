<?php

namespace Database\Seeders;

use App\Models\RequestType;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Str;

class RequestTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $carlos   = User::where('email', 'carlos.mendes@tramita.com.br')->first();
        $fernanda = User::where('email', 'fernanda.lima@tramita.com.br')->first();
        $ricardo  = User::where('email', 'ricardo.souza@tramita.com.br')->first();

        $types = [
            [
                'name'        => 'Solicitação de Compra',
                'description' => 'Requisição de materiais, equipamentos ou serviços',
                'color'       => '#6366f1',
                'icon'        => 'heroicon-o-shopping-cart',
                'sla_hours'   => 48,
                'approvers'   => [[$carlos, 1], [$ricardo, 2]],
            ],
            [
                'name'        => 'Reembolso de Despesas',
                'description' => 'Solicitação de reembolso de gastos realizados',
                'color'       => '#10b981',
                'icon'        => 'heroicon-o-banknotes',
                'sla_hours'   => 72,
                'approvers'   => [[$carlos, 1]],
            ],
            [
                'name'        => 'Férias',
                'description' => 'Solicitação de período de férias',
                'color'       => '#f59e0b',
                'icon'        => 'heroicon-o-sun',
                'sla_hours'   => 120,
                'approvers'   => [[$fernanda, 1]],
            ],
            [
                'name'        => 'Acesso a Sistema',
                'description' => 'Solicitação de acesso ou permissão em sistemas internos',
                'color'       => '#3b82f6',
                'icon'        => 'heroicon-o-lock-open',
                'sla_hours'   => 24,
                'approvers'   => [[$ricardo, 1]],
            ],
            [
                'name'        => 'Contratação de Fornecedor',
                'description' => 'Aprovação para contratação de novos fornecedores',
                'color'       => '#ef4444',
                'icon'        => 'heroicon-o-building-office',
                'sla_hours'   => 168,
                'approvers'   => [[$carlos, 1], [$fernanda, 2], [$ricardo, 3]],
            ],
        ];

        foreach ($types as $data) {
            $approvers = $data['approvers'];
            unset($data['approvers']);

            $type = RequestType::create(array_merge($data, [
                'slug' => Str::slug($data['name']),
            ]));

            foreach ($approvers as [$user, $order]) {
                $type->approvers()->attach($user->id, ['order' => $order]);
            }
        }

    }
}
