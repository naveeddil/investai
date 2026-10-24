<?php

namespace App\Filament\Resources\Articles\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class ArticleForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Article')->schema([
                    TextInput::make('title')->required()->maxLength(180),
                    TextInput::make('slug')->required()->maxLength(180)->unique(ignoreRecord: true),
                    Select::make('kind')->options(['insight' => 'Insight', 'weekly_tip' => 'Weekly tip', 'legacy' => 'Legacy import'])->required(),
                    Select::make('status')->options(['draft' => 'Draft', 'review' => 'In review', 'published' => 'Published', 'archived' => 'Archived'])->required(),
                    Textarea::make('excerpt')->required()->rows(3)->maxLength(500)->columnSpanFull(),
                    Textarea::make('body')->required()->rows(18)->columnSpanFull(),
                    TextInput::make('featured_image')->placeholder('/images/editorial/example.jpg')->maxLength(255),
                    TextInput::make('featured_image_alt')->maxLength(180),
                    TextInput::make('source_url')->url()->maxLength(255),
                    Toggle::make('generated_by_ai')->disabled(),
                ])->columns(2),
                Section::make('Editorial approval')->schema([
                    Textarea::make('review_notes')->rows(4)->columnSpanFull(),
                    DateTimePicker::make('reviewed_at')->helperText('Required before a published article can appear publicly.'),
                    DateTimePicker::make('published_at'),
                    DateTimePicker::make('scheduled_for'),
                ])->columns(3),
            ]);
    }
}
