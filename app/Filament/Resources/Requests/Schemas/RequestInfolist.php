<?php

namespace App\Filament\Resources\Requests\Schemas;

use App\Models\Request;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class RequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('code'),
                TextEntry::make('request_type_id')
                    ->numeric(),
                TextEntry::make('requester.name')
                    ->label('Requester'),
                TextEntry::make('department.name')
                    ->label('Department'),
                TextEntry::make('title'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('priority')
                    ->badge(),
                TextEntry::make('form_data')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('submitted_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('resolved_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('sla_target_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('due_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Request $record): bool => $record->trashed()),
            ]);
    }
}
