<?php

namespace App\Filament\Resources\Articles\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ArticlesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')->searchable()->sortable()->wrap(),
                TextColumn::make('kind')->badge()->sortable(),
                TextColumn::make('status')->badge()->sortable(),
                IconColumn::make('reviewed_at')->label('Reviewed')->boolean(fn ($state): bool => filled($state)),
                TextColumn::make('published_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('kind')->options(['insight' => 'Insight', 'weekly_tip' => 'Weekly tip', 'legacy' => 'Legacy import']),
                SelectFilter::make('status')->options(['draft' => 'Draft', 'review' => 'In review', 'published' => 'Published', 'archived' => 'Archived']),
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
