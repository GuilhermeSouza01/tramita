<?php

namespace App\Filament\Resources\Requests\Schemas;

use App\Enums\Priority;
use App\Enums\RequestStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Schemas\Components\Group;

class RequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Group::make()
                    ->schema([
                         Section::make('Informações Básicas')
                            ->columns(1)
                            ->compact(false)
                            // ->maxWidth('2xl')
                            ->description('Preencha as informações básicas da solicitação.')
                            ->icon('heroicon-o-information-circle')
                            ->schema([
                                TextInput::make('title')
                                    ->label('Título da Solicitação')
                                    ->placeholder('Ex: Solicitação de Acesso ao Sistema XYZ')
                                    ->required(),
                                Select::make('request_type_id')
                                    ->label('Tipo de Solicitação')
                                    ->relationship('requestType', 'name')
                                    ->required()
                                    ->searchable()
                                    ->preload()
                                    ->placeholder('Selecione um tipo de solicitação'),

                                Grid::make(2)
                                    ->schema([
                                        Select::make('department_id')
                                            ->label('Departamento')
                                            ->searchable()
                                            ->relationship('department', 'name')
                                            ->preload()
                                            ->required(),


                                        ToggleButtons::make('priority')
                                            ->label('Prioridade')
                                            ->options(
                                                collect(Priority::cases())
                                                    ->mapWithKeys(fn ($p) => [$p->value => $p->label()])
                                                    ->toArray()
                                            )
                                            ->inline()
                                            ->inlineLabel(false)
                                            ->default('medium')
                                            ->required()
                                    ]),

                            ]),

                        Section::make('Descrição detalhada')
                            ->description(null)
                            ->icon('heroicon-o-document-text')
                            // ->maxWidth('2xl')
                            ->schema([
                                Textarea::make('description')
                                    ->label('Descrição da Solicitação')
                                    ->placeholder('Descreva aqui o motivo da solicitação e os detalhes técnicos necessários...')
                                    ->minLength(50)
                                    ->maxLength(2000)
                                    ->default(null)
                                    ->rows(6)
                                    ->columnSpanFull()
                                    ->live(debounce: 300),
                            ]),

                        Section::make('Anexos')
                            ->icon('heroicon-o-paper-clip')
                            // ->maxWidth('2xl')
                            ->schema([
                                FileUpload::make('attachments')
                                    ->label(false)
                                    // ->helperText('Suporta PDF, JPG, PNG e DOCX até 15MB.')
                                    ->placeholder('Arraste e solte arquivos aqui ou clique para selecionar')
                                    ->multiple()
                                    ->acceptedFileTypes(['application/pdf', 'image/jpeg', 'image/png', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'])
                                    ->maxSize(15360) // 15MB em KB
                                    ->imagePreviewHeight('80')
                                    ->panelLayout('compact')
                                    ->uploadButtonPosition('left')
                                    ->uploadProgressIndicatorPosition('left')
                                    ->columnSpanFull()
                                    ->uploadingMessage('Enviando arquivo...')
                                    ->panelLayout('compact'),
                            ]),
                    ])
                    ->extraAttributes([
                        'class' => 'mx-auto max-w-4xl w-full'
                    ])


            ]);
    }
}
