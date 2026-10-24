<?php

namespace App\Filament\Resources\TeamMembers\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TeamMemberForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->required()->maxLength(120),
                TextInput::make('role')->required()->maxLength(120),
                Textarea::make('bio')->rows(5)->maxLength(1200)->columnSpanFull(),
                FileUpload::make('photo')->image()->imageEditor()->disk('public')->directory('team')->maxSize(4096),
                TextInput::make('photo_alt')->maxLength(180),
                TextInput::make('linkedin_url')->url()->maxLength(255),
                TextInput::make('sort_order')->numeric()->default(0),
                Toggle::make('is_visible')->label('Published on About page')->default(false),
            ]);
    }
}
