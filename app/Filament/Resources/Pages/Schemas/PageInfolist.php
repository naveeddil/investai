<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Models\Page;
use Filament\Infolists\Components\IconEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class PageInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title'),
                TextEntry::make('slug'),
                TextEntry::make('navigation_label')
                    ->placeholder('-'),
                TextEntry::make('description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('page_type'),
                TextEntry::make('status'),
                IconEntry::make('is_system_page')
                    ->boolean(),
                IconEntry::make('deletion_protected')
                    ->boolean(),
                IconEntry::make('show_in_navigation')
                    ->boolean(),
                IconEntry::make('show_in_footer')
                    ->boolean(),
                IconEntry::make('index_by_search_engines')
                    ->boolean(),
                TextEntry::make('blocks')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('featured_media_id')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('seo_title')
                    ->placeholder('-'),
                TextEntry::make('seo_description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('canonical_url')
                    ->placeholder('-'),
                TextEntry::make('open_graph_title')
                    ->placeholder('-'),
                TextEntry::make('open_graph_description')
                    ->placeholder('-')
                    ->columnSpanFull(),
                TextEntry::make('open_graph_media_id')
                    ->numeric()
                    ->placeholder('-'),
                IconEntry::make('robots_follow')
                    ->boolean(),
                TextEntry::make('published_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('scheduled_publish_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('created_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('updated_by')
                    ->numeric()
                    ->placeholder('-'),
                TextEntry::make('deleted_at')
                    ->dateTime()
                    ->visible(fn (Page $record): bool => $record->trashed()),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
