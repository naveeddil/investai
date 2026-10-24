<?php

namespace App\Filament\Resources\SiteSettings\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class SiteSettingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('group')
                    ->options(['appearance' => 'Appearance', 'company' => 'Company', 'contact' => 'Contact', 'seo' => 'SEO'])
                    ->required(),
                TextInput::make('key')
                    ->required(),
                Select::make('value')
                    ->label('Default website theme')
                    ->options([
                        'midnight' => 'Midnight',
                        'cobalt' => 'Cobalt',
                        'ivory' => 'Ivory',
                    ])
                    ->required(),
                Select::make('type')
                    ->options(['string' => 'Text', 'boolean' => 'Yes / no', 'json' => 'Structured JSON'])
                    ->required()->default('string'),
            ]);
    }
}
