<?php

namespace App\Filament\Resources\Users\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UsersOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make(
                'Total de Usuários',
                User::count()
            )
                ->description('Usuários cadastrados')
                ->descriptionIcon('heroicon-m-users')
                ->color('primary'),

            Stat::make(
                'Usuários Ativos',
                User::where('is_active', true)->count()
            )
                ->description('Contas ativas')
                ->descriptionIcon('heroicon-m-check-circle')
                ->color('success'),

            Stat::make(
                'Usuários Inativos',
                User::where('is_active', false)->count()
            )
                ->description('Contas desativadas')
                ->descriptionIcon('heroicon-m-x-circle')
                ->color('danger'),
        ];
    }
}
