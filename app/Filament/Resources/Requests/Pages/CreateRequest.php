<?php

namespace App\Filament\Resources\Requests\Pages;

use App\Enums\RequestStatus;
use App\Filament\Resources\Requests\RequestResource;
use BackedEnum;
use Filament\Resources\Pages\CreateRecord;
use Filament\Support\Enums\Alignment;
use Filament\Support\Icons\Heroicon;
use Override;

class CreateRequest extends CreateRecord
{
    protected array $pendingFiles = [];

    protected static string $resource = RequestResource::class;


    public function getFormActionsAlignment(): string|Alignment
    {
        return "end";
    }

    protected static bool $canCreateAnother = false;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->pendingFiles = $data['attachments'] ?? [];
        unset($data['attachments']);
        // Adiciona o ID do usuário autenticado como solicitante
        $data['requester_id'] = auth()->id();
        $data['status'] = RequestStatus::Draft; // Define o status inicial como "draft"

        return $data;
    }

    protected function afterCreate(): void
    {
        foreach ($this->pendingFiles as $file) {
            $path = $file;
            $name = $path;
            $mime = 'application/octet-stream';
            $size = 0;
            if (file_exists(storage_path('app/public/' . $path))) {
                $fullPath = storage_path('app/public/' . $path);
                $name = basename($path);
                $mime = mime_content_type($fullPath) ?: 'application/octet-stream';
                $size = filesize($fullPath);
            }
            $this->record->attachments()->create([
                'uploaded_by' => auth()->id(),
                'name' => $name,
                'path' => $path,
                'disk' => 'public',
                'mime_type' => $mime,
                'size' => $size,
            ]);
        }
    }

}
