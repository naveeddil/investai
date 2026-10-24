<?php

namespace App\Filament\Resources\WaitlistSignups\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class WaitlistSignupForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                TextInput::make('audience_type')
                    ->required(),
                Toggle::make('consent')
                    ->required(),
                TextInput::make('consent_version')
                    ->required(),
                DateTimePicker::make('consented_at'),
                TextInput::make('status')
                    ->required()
                    ->default('active'),
                DateTimePicker::make('submitted_at')
                    ->required(),
                DateTimePicker::make('unsubscribed_at'),
            ]);
    }
}
