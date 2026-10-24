<?php

namespace App\Filament\Resources\Redirects\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RedirectForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('source_path')
                    ->required(),
                TextInput::make('destination')
                    ->required(),
                TextInput::make('http_status')
                    ->required()
                    ->numeric()
                    ->default(301),
                Toggle::make('enabled')
                    ->required(),
            ]);
    }
}
