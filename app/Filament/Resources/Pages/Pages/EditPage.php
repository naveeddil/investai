<?php

namespace App\Filament\Resources\Pages\Pages;

use App\Enums\PageStatus;
use App\Filament\Resources\Pages\PageResource;
use App\Models\PageVersion;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Resources\Pages\EditRecord;

class EditPage extends EditRecord
{
    protected static string $resource = PageResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $user = auth()->user();
        $data['updated_by'] = $user?->id;

        if ($this->record->deletion_protected) {
            $data['slug'] = $this->record->slug;
            $data['page_type'] = $this->record->page_type;
        }

        if (! $user?->role->canPublish() && $data['status'] === PageStatus::Published->value) {
            $data['status'] = $this->record->status === PageStatus::Published
                ? PageStatus::Published->value
                : PageStatus::Draft->value;
        }

        if ($data['status'] === PageStatus::Published->value && ! $this->record->published_at) {
            $data['published_at'] = now();
        }

        return $data;
    }

    protected function afterSave(): void
    {
        $versionNumber = ((int) $this->record->versions()->max('version_number')) + 1;

        PageVersion::create([
            'page_id' => $this->record->id,
            'version_number' => $versionNumber,
            'action' => 'edited',
            'snapshot' => $this->record->fresh()->toArray(),
            'user_id' => auth()->id(),
        ]);
    }

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }
}
