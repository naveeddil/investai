<?php

namespace App\Filament\Resources\NewsletterSubscribers;

use App\Filament\Resources\NewsletterSubscribers\Pages\EditNewsletterSubscriber;
use App\Filament\Resources\NewsletterSubscribers\Pages\ListNewsletterSubscribers;
use App\Models\NewsletterSubscriber;
use BackedEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class NewsletterSubscriberResource extends Resource
{
    protected static ?string $model = NewsletterSubscriber::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedEnvelope;

    protected static ?string $navigationLabel = 'Email subscribers';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')->maxLength(120), TextInput::make('email')->email()->required(),
            Toggle::make('monthly_newsletter'), Toggle::make('weekly_tips'),
            Select::make('status')->options(['pending' => 'Pending confirmation', 'active' => 'Active', 'unsubscribed' => 'Unsubscribed'])->required(),
            DateTimePicker::make('consented_at')->disabled(), DateTimePicker::make('confirmed_at')->disabled(), DateTimePicker::make('unsubscribed_at')->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->searchable(), TextColumn::make('email')->searchable(),
            IconColumn::make('monthly_newsletter')->label('Monthly')->boolean(), IconColumn::make('weekly_tips')->label('Weekly')->boolean(),
            TextColumn::make('status')->badge()->sortable(), TextColumn::make('confirmed_at')->dateTime()->sortable(),
        ])->filters([SelectFilter::make('status')->options(['pending' => 'Pending', 'active' => 'Active', 'unsubscribed' => 'Unsubscribed'])]);
    }

    public static function getPages(): array
    {
        return ['index' => ListNewsletterSubscribers::route('/'), 'edit' => EditNewsletterSubscriber::route('/{record}/edit')];
    }
}
