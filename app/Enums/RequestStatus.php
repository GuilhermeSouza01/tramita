<?php

namespace App\Enums;

enum RequestStatus: string
{
    case Draft = 'draft';
    case Pending = 'pending';
    case UnderReview = 'under_review';
    case AdjustmentRequested = 'adjustment_requested';
    case Approved = 'approved';
    case Rejected = 'rejected';
    case Cancelled = 'cancelled';

    public function label(): string
    {
        return match($this) {
            self::Draft               => 'Rascunho',
            self::Pending             => 'Pendente',
            self::UnderReview         => 'Em análise',
            self::AdjustmentRequested => 'Ajuste solicitado',
            self::Approved            => 'Aprovada',
            self::Rejected            => 'Rejeitada',
            self::Cancelled           => 'Cancelada',
        };
    }

    public function color(): string
    {
        return match($this) {
            self::Draft               => 'gray',
            self::Pending             => 'warning',
            self::UnderReview         => 'info',
            self::AdjustmentRequested => 'warning',
            self::Approved            => 'success',
            self::Rejected            => 'danger',
            self::Cancelled           => 'gray',
        };
    }

     public function isTerminal(): bool
    {
        return in_array($this, [
            self::Approved,
            self::Rejected,
            self::Cancelled,
        ]);
    }
}
