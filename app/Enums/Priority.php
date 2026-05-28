<?php

namespace App\Enums;

enum Priority: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
    case Critical = 'critical';

    public function label(): string
    {
        return match ($this) {
            self::Low => 'Baixa',
            self::Medium => 'Média',
            self::High => 'Alta',
            self::Critical => 'Crítica',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Low => 'success',
            self::Medium => 'warning',
            self::High => 'danger',
            self::Critical => 'danger',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Low => 'heroicon-o-arrow-down',
            self::Medium => 'heroicon-o-minus',
            self::High => 'heroicon-o-arrow-up',
            self::Critical => 'heroicon-o-exclamation-triangle',
        };
    }

    public function hexColor(): string
    {
        return match ($this) {
            self::Low => '#22c55e',
            self::Medium => '#f59e0b',
            self::High => '#ef4444',
            self::Critical => '#dc2626',
        };
    }
}
