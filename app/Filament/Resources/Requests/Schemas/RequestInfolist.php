<?php

namespace App\Filament\Resources\Requests\Schemas;

use App\Enums\Priority;
use App\Enums\RequestStatus;
use App\Models\Request;
use Filament\Infolists\Components\RepeatableEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Flex;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Livewire;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Enums\FontWeight;
use Filament\Support\Enums\IconPosition;
use Illuminate\Support\HtmlString;

class RequestInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Flex::make([

                    // =========================================================
                    // COLUNA PRINCIPAL (cresce para preencher o espaço)
                    // =========================================================
                    Group::make([

                        // Cabeçalho: Código + Título
                        Section::make()
                            ->schema([
                                TextEntry::make('code')
                                    ->label('')
                                    ->badge()
                                    ->color('gray')
                                    ->weight(FontWeight::Medium)
                                    ->formatStateUsing(fn (string $state) => strtoupper($state)),

                                TextEntry::make('title')
                                    ->label('')
                                    ->weight(FontWeight::Bold)
                                    ->columnSpanFull(),
                            ])
                            ->compact(),

                        // Descrição da Solicitação
                        Section::make('Descrição da Solicitação')
                            ->icon('heroicon-o-document-text')
                            ->schema([
                                TextEntry::make('description')
                                    ->label('')
                                    ->markdown()
                                    ->prose()
                                    ->placeholder('Nenhuma descrição fornecida.')
                                    ->columnSpanFull(),
                            ]),

                        // Informações Gerais
                        Section::make('Informações da Solicitação')
                            ->icon('heroicon-o-information-circle')
                            ->collapsible()
                            ->schema([
                                Grid::make(2)
                                    ->schema([
                                        TextEntry::make('requestType.name')
                                            ->label('Tipo de Solicitação')
                                            ->icon('heroicon-o-tag')
                                            ->iconColor('gray')
                                            ->weight(FontWeight::Medium)
                                            ->placeholder('—'),

                                        TextEntry::make('department.name')
                                            ->label('Departamento')
                                            ->icon('heroicon-o-building-office-2')
                                            ->iconColor('gray')
                                            ->weight(FontWeight::Medium)
                                            ->placeholder('—'),

                                        TextEntry::make('requester.name')
                                            ->label('Solicitante')
                                            ->icon('heroicon-o-user-circle')
                                            ->iconColor('gray')
                                            ->weight(FontWeight::Medium)
                                            ->placeholder('—'),

                                        TextEntry::make('created_at')
                                            ->label('Data de Criação')
                                            ->icon('heroicon-o-calendar-days')
                                            ->iconColor('gray')
                                            ->dateTime('d/m/Y \à\s H:i')
                                            ->weight(FontWeight::Medium),
                                    ]),
                            ]),

                        // Progresso do Fluxo (placeholder Livewire)
                        Section::make('Progresso do Fluxo')
                            ->icon('heroicon-o-arrow-path')
                            ->collapsible()
                            ->schema([
                              Livewire::make('approval-timeline', [
                                'requestId' => fn (Request $record) => $record?->id,
                              ])
                            ]),

                        // Comentários e Atividade (placeholder Livewire)
                        Section::make('Comentários e Atividade')
                            ->icon('heroicon-o-chat-bubble-left-right')
                            ->collapsible()
                            ->schema([
                                TextEntry::make('_comments_placeholder')
                                    ->label('')
                                    ->default('')
                                    ->formatStateUsing(fn () => new HtmlString(<<<HTML
                                        <div class="flex flex-col items-center justify-center gap-3 py-10 text-center">
                                            <div class="flex h-12 w-12 items-center justify-center rounded-full bg-gray-100 dark:bg-white/5">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="h-6 w-6 text-gray-400">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H8.25m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0H12m4.125 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 0 1 .778-.332 48.294 48.294 0 0 0 5.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                                                </svg>
                                            </div>
                                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Feed de atividades em desenvolvimento</p>
                                            <p class="text-xs text-gray-400 dark:text-gray-500">Componente Livewire será implementado em breve</p>
                                        </div>
                                    HTML))
                                    ->html(),
                            ]),

                    ]),

                    // =========================================================
                    // SIDEBAR (largura fixa — grow(false))
                    // =========================================================
                    Group::make([

                        // Card do Solicitante
                        Section::make()
                            ->schema([
                                TextEntry::make('requester.name')
                                    ->label('Solicitante')
                                    ->weight(FontWeight::SemiBold)
                                    ->icon('heroicon-o-user-circle')
                                    ->iconColor('primary'),

                                TextEntry::make('department.name')
                                    ->label('Departamento')
                                    ->icon('heroicon-o-building-office-2')
                                    ->iconColor('gray')
                                    ->placeholder('—'),

                                Grid::make(1)
                                    ->schema([
                                        TextEntry::make('priority')
                                            ->label('Prioridade')
                                            ->badge()
                                            ->formatStateUsing(fn (Priority $state) => $state->label())
                                            ->color(fn (Priority $state) => $state->color())
                                            ->icon(fn (Priority $state) => $state->icon())
                                            ->iconPosition(IconPosition::Before),

                                    ]),
                            ])
                            ->compact(),

                        // Status da Solicitação
                        Section::make('Andamento')
                            ->icon('heroicon-o-chart-bar')
                            ->iconColor('primary')
                            ->schema([
                                TextEntry::make('status')
                                    ->label('Status')
                                    ->badge()
                                    ->formatStateUsing(fn (RequestStatus $state) => $state->label())
                                    ->color(fn (RequestStatus $state) => $state->color()),
                            ]),

                        // SLA
                        Section::make('SLA')
                            ->icon('heroicon-o-clock')
                            ->iconColor('warning')
                            ->schema([
                                TextEntry::make('sla_target_at')
                                    ->label('Prazo SLA')
                                    ->icon('heroicon-o-flag')
                                    ->iconColor('warning')
                                    ->dateTime('d/m/Y \à\s H:i')
                                    ->placeholder('Não definido'),

                                TextEntry::make('due_at')
                                    ->label('Vencimento')
                                    ->icon('heroicon-o-calendar')
                                    ->iconColor('danger')
                                    ->dateTime('d/m/Y \à\s H:i')
                                    ->placeholder('Não definido'),

                                TextEntry::make('submitted_at')
                                    ->label('Data de Envio')
                                    ->icon('heroicon-o-paper-airplane')
                                    ->iconColor('info')
                                    ->dateTime('d/m/Y \à\s H:i')
                                    ->placeholder('Não enviado'),

                                TextEntry::make('resolved_at')
                                    ->label('Data de Resolução')
                                    ->icon('heroicon-o-check-circle')
                                    ->iconColor('success')
                                    ->dateTime('d/m/Y \à\s H:i')
                                    ->placeholder('Pendente'),
                            ]),

                        // Aprovadores
                        Section::make('Aprovadores')
                            ->icon('heroicon-o-user-group')
                            ->iconColor('primary')
                            ->schema([
                                RepeatableEntry::make('approvalSteps')
                                    ->label('')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                TextEntry::make('approver.name')
                                                    ->label('')
                                                    ->icon('heroicon-o-user')
                                                    ->iconColor('gray')
                                                    ->weight(FontWeight::Medium),

                                                TextEntry::make('status')
                                                    ->label('')
                                                    ->badge()
                                                    ->formatStateUsing(fn ($state) => match ($state) {
                                                        'approved' => 'Aprovado',
                                                        'rejected' => 'Rejeitado',
                                                        'pending'  => 'Pendente',
                                                        'skipped'  => 'Ignorado',
                                                        default    => ucfirst($state ?? 'Pendente'),
                                                    })
                                                    ->color(fn ($state) => match ($state) {
                                                        'approved' => 'success',
                                                        'rejected' => 'danger',
                                                        'pending'  => 'warning',
                                                        'skipped'  => 'gray',
                                                        default    => 'gray',
                                                    }),
                                            ]),
                                    ])
                                    ->contained(false)
                                    ->placeholder('Nenhum aprovador vinculado.'),
                            ]),

                        // Anexos
                        Section::make('Anexos')
                            ->icon('heroicon-o-paper-clip')
                            ->iconColor('gray')
                            ->schema([
                                RepeatableEntry::make('attachments')
                                    ->label('')
                                    ->schema([
                                        TextEntry::make('original_name')
                                            ->label('')
                                            ->icon(fn ($record) => self::fileIcon($record?->mime_type ?? ''))
                                            ->iconColor('primary')
                                            ->weight(FontWeight::Medium)
                                            ->formatStateUsing(function ($state, $record) {
                                                $size = $record?->size
                                                    ? ' · ' . number_format($record->size / 1024 / 1024, 1) . ' MB'
                                                    : '';

                                                return $state . $size;
                                            }),
                                    ])
                                    ->contained(false)
                                    ->placeholder('Nenhum anexo encontrado.'),
                            ]),

                        // Auditoria (colapsada por padrão)
                        Section::make('Auditoria')
                            ->icon('heroicon-o-shield-check')
                            ->iconColor('gray')
                            ->collapsed()
                            ->collapsible()
                            ->schema([
                                TextEntry::make('updated_at')
                                    ->label('Última Atualização')
                                    ->dateTime('d/m/Y \à\s H:i')
                                    ->icon('heroicon-o-pencil-square')
                                    ->iconColor('gray'),

                                TextEntry::make('deleted_at')
                                    ->label('Excluído em')
                                    ->dateTime('d/m/Y \à\s H:i')
                                    ->icon('heroicon-o-trash')
                                    ->iconColor('danger')
                                    ->visible(fn (Request $record) => $record->trashed()),
                            ]),

                    ])
                    ->grow(false),

                ])
                ->from('md')
                ->columnSpanFull(),
            ]);
    }

    private static function fileIcon(string $mimeType): string
    {
        if (str_contains($mimeType, 'pdf'))         return 'heroicon-o-document';
        if (str_contains($mimeType, 'spreadsheet')) return 'heroicon-o-table-cells';
        if (str_contains($mimeType, 'excel'))       return 'heroicon-o-table-cells';
        if (str_contains($mimeType, 'csv'))         return 'heroicon-o-table-cells';
        if (str_contains($mimeType, 'word'))        return 'heroicon-o-document-text';
        if (str_contains($mimeType, 'document'))    return 'heroicon-o-document-text';
        if (str_contains($mimeType, 'image'))       return 'heroicon-o-photo';
        if (str_contains($mimeType, 'zip'))         return 'heroicon-o-archive-box';
        if (str_contains($mimeType, 'rar'))         return 'heroicon-o-archive-box';
        if (str_contains($mimeType, 'tar'))         return 'heroicon-o-archive-box';
        if (str_contains($mimeType, 'video'))       return 'heroicon-o-film';
        if (str_contains($mimeType, 'audio'))       return 'heroicon-o-musical-note';

        return 'heroicon-o-paper-clip';
    }
}
