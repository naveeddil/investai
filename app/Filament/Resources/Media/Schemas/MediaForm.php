<?php

namespace App\Filament\Resources\Media\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class MediaForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('disk')
                    ->required()
                    ->default('public'),
                TextInput::make('path')
                    ->required(),
                TextInput::make('original_filename')
                    ->required(),
                TextInput::make('mime_type')
                    ->required(),
                TextInput::make('size')
                    ->required()
                    ->numeric(),
                TextInput::make('width')
                    ->numeric(),
                TextInput::make('height')
                    ->numeric(),
                TextInput::make('alt_text'),
                Textarea::make('caption')
                    ->columnSpanFull(),
                TextInput::make('uploaded_by')
                    ->numeric(),
            ]);
    }
}
