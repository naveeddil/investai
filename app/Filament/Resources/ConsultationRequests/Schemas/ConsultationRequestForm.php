<?php

namespace App\Filament\Resources\ConsultationRequests\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ConsultationRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')->disabled(),
                TextInput::make('email')->disabled(),
                TextInput::make('phone')->disabled(),
                TextInput::make('company')->disabled(),
                TextInput::make('preferred_contact')->disabled(),
                TextInput::make('preferred_time')->disabled(),
                TextInput::make('topic')->disabled(),
                Textarea::make('message')->disabled()->columnSpanFull(),
                Select::make('status')->options(['new' => 'New', 'contacted' => 'Contacted', 'closed' => 'Closed'])->required(),
            ]);
    }
}
