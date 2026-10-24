<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
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
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Select::make('role')
                    ->options(UserRole::class)
                    ->default('viewer')
                    ->required(),
                Select::make('status')
                    ->options(UserStatus::class)
                    ->default('invited')
                    ->required(),
                DateTimePicker::make('invited_at'),
                DateTimePicker::make('activated_at'),
                DateTimePicker::make('last_login_at'),
            ]);
    }
}
