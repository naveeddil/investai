<?php

namespace App\Filament\Resources\Navigations\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class NavigationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('key')
                    ->required(),
            ]);
    }
}
