<?php

namespace App\Filament\Resources\Pages\Pages;

use App\Enums\PageStatus;
use App\Filament\Resources\Pages\PageResource;
use App\Models\PageVersion;
use Filament\Resources\Pages\CreateRecord;

class CreatePage extends CreateRecord
{
    protected static string $resource = PageResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $user = auth()->user();
        $data['created_by'] = $user?->id;
        $data['updated_by'] = $user?->id;

        if (! $user?->role->canPublish() && $data['status'] === PageStatus::Published->value) {
            $data['status'] = PageStatus::Draft->value;
        }

        if ($data['status'] === PageStatus::Published->value) {
            $data['published_at'] = now();
        }

        return $data;
    }

    protected function afterCreate(): void
    {
        PageVersion::create([
            'page_id' => $this->record->id,
            'version_number' => 1,
            'action' => 'created',
            'snapshot' => $this->record->toArray(),
            'user_id' => auth()->id(),
        ]);
    }
}
