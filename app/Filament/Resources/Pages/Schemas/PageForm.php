<?php

namespace App\Filament\Resources\Pages\Schemas;

use App\Enums\PageStatus;
use Filament\Forms\Components\Builder;
use Filament\Forms\Components\Builder\Block;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PageForm
{
    /**
     * Icons available to blocks; each must exist in resources/views/components/glyph.blade.php.
     *
     * @var array<string, string>
     */
    private const ICONS = [
        'briefcase' => 'Briefcase',
        'chart' => 'Bar chart',
        'trend' => 'Trend line',
        'sparkles' => 'Sparkles (AI)',
        'workflow' => 'Workflow',
        'shield' => 'Shield',
        'scale' => 'Scales',
        'building' => 'Building',
        'landmark' => 'Institution',
        'globe' => 'Globe',
        'users' => 'People',
        'lightbulb' => 'Idea',
        'search' => 'Search',
        'document' => 'Document',
        'clock' => 'Clock',
        'lock' => 'Lock',
        'cpu' => 'Chip',
        'database' => 'Database',
        'chat' => 'Conversation',
        'flag' => 'Flag',
        'target' => 'Target',
        'layers' => 'Layers',
        'banknote' => 'Banknote',
        'heart' => 'Heart',
        'leaf' => 'Leaf',
        'eye' => 'Eye',
        'calculator' => 'Calculator',
        'clipboard' => 'Clipboard',
        'check' => 'Check',
    ];

    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Page')->schema([
                TextInput::make('title')->required()->maxLength(160),
                TextInput::make('slug')->required()->maxLength(160)->unique(ignoreRecord: true),
                TextInput::make('navigation_label')->maxLength(80),
                Textarea::make('description')->rows(3)->columnSpanFull(),
                Select::make('page_type')->options(['standard' => 'Standard', 'home' => 'Homepage', 'legal' => 'Legal'])->required()->default('standard'),
                Select::make('status')->options(PageStatus::class)->required()->default(PageStatus::Draft),
                DateTimePicker::make('scheduled_publish_at'),
                Toggle::make('show_in_navigation'),
                Toggle::make('show_in_footer'),
            ])->columns(2),
            Section::make('Page builder')->schema([
                Builder::make('blocks')->blocks([
                    Block::make('hero')->schema([
                        TextInput::make('eyebrow')->maxLength(80),
                        TextInput::make('heading')->required()->maxLength(180),
                        Textarea::make('text')->rows(4),
                        TextInput::make('button_label')->maxLength(60),
                        TextInput::make('button_url')->maxLength(255),
                        TextInput::make('secondary_label')->maxLength(60),
                        TextInput::make('secondary_url')->maxLength(255),
                        Select::make('size')->options(['full' => 'Full height', 'compact' => 'Compact'])->default('full'),
                        Select::make('visual')->options(['' => 'None', 'dashboard' => 'Analytics dashboard illustration'])->helperText('Used when no image path is set.'),
                        TextInput::make('image')->label('Image path')->placeholder('/images/editorial/example.jpg')->maxLength(255),
                        TextInput::make('image_alt')->label('Image alternative text')->maxLength(180),
                        Repeater::make('highlights')->schema([
                            TextInput::make('text')->required()->maxLength(60),
                        ])->maxItems(6)->columnSpanFull(),
                        self::backgroundSelect(),
                    ])->columns(2),
                    Block::make('rich_text')->schema([
                        TextInput::make('heading')->maxLength(180),
                        Textarea::make('text')->required()->rows(8),
                        self::backgroundSelect(),
                    ]),
                    Block::make('feature_cards')->schema([
                        TextInput::make('heading')->required()->maxLength(180),
                        Textarea::make('intro')->rows(3),
                        Repeater::make('items')->schema([
                            TextInput::make('heading')->required()->maxLength(120),
                            Select::make('icon')->options(self::ICONS)->placeholder('Numbered'),
                            Textarea::make('text')->required()->rows(3)->columnSpanFull(),
                            TextInput::make('image')->label('Image path (optional)')->maxLength(255),
                            TextInput::make('image_alt')->label('Image alternative text')->maxLength(180),
                        ])->minItems(1)->maxItems(6)->columns(2),
                        self::backgroundSelect(),
                    ]),
                    Block::make('service_grid')->schema([
                        TextInput::make('eyebrow')->maxLength(80),
                        TextInput::make('heading')->required()->maxLength(180),
                        Textarea::make('intro')->rows(3),
                        Repeater::make('items')->schema([
                            TextInput::make('heading')->required()->maxLength(120),
                            Select::make('icon')->options(self::ICONS)->default('sparkles'),
                            Textarea::make('text')->required()->rows(3)->columnSpanFull(),
                            TextInput::make('link_label')->maxLength(60),
                            TextInput::make('link_url')->maxLength(255),
                        ])->minItems(1)->maxItems(8)->columns(2),
                        self::backgroundSelect(),
                    ]),
                    Block::make('metrics')->schema([
                        TextInput::make('heading')->maxLength(120)->helperText('Optional small heading above the figures. Only publish figures that can be evidenced.'),
                        Repeater::make('items')->schema([
                            TextInput::make('value')->required()->maxLength(20),
                            TextInput::make('label')->required()->maxLength(120),
                        ])->minItems(1)->maxItems(4)->columns(2),
                        self::backgroundSelect(),
                    ]),
                    Block::make('split_content')->schema([
                        TextInput::make('eyebrow')->maxLength(80),
                        TextInput::make('heading')->required()->maxLength(180),
                        Textarea::make('text')->required()->rows(5),
                        TextInput::make('image')->label('Image path')->required()->placeholder('/images/editorial/example.jpg')->maxLength(255),
                        TextInput::make('image_alt')->label('Image alternative text')->required()->maxLength(180),
                        Select::make('image_side')->options(['left' => 'Left', 'right' => 'Right'])->default('left')->required(),
                        TextInput::make('link_label')->maxLength(60),
                        TextInput::make('link_url')->maxLength(255),
                        Repeater::make('points')->schema([
                            TextInput::make('text')->required()->maxLength(180),
                        ])->maxItems(7),
                        self::backgroundSelect(),
                    ])->columns(2),
                    Block::make('process')->schema([
                        TextInput::make('eyebrow')->maxLength(80),
                        TextInput::make('heading')->required()->maxLength(180),
                        Textarea::make('intro')->rows(3),
                        Repeater::make('steps')->schema([
                            TextInput::make('heading')->required()->maxLength(120),
                            Textarea::make('text')->required()->rows(3),
                        ])->minItems(2)->maxItems(5)->columns(2),
                        self::backgroundSelect(),
                    ]),
                    Block::make('timeline')->schema([
                        TextInput::make('eyebrow')->maxLength(80),
                        TextInput::make('heading')->required()->maxLength(180),
                        Textarea::make('intro')->rows(3),
                        Repeater::make('items')->schema([
                            TextInput::make('phase')->maxLength(60),
                            TextInput::make('heading')->required()->maxLength(120),
                            Select::make('status')->options(['complete' => 'Complete', 'active' => 'In progress', 'planned' => 'Planned'])->default('planned'),
                            Select::make('icon')->options(self::ICONS)->default('flag'),
                            Textarea::make('text')->required()->rows(3)->columnSpanFull(),
                        ])->minItems(2)->maxItems(5)->columns(2),
                        Textarea::make('note')->rows(2),
                        self::backgroundSelect(),
                    ]),
                    Block::make('comparison')->schema([
                        TextInput::make('eyebrow')->maxLength(80),
                        TextInput::make('heading')->required()->maxLength(180),
                        Textarea::make('intro')->rows(3),
                        TextInput::make('topic_label')->maxLength(40),
                        TextInput::make('left_label')->maxLength(60),
                        TextInput::make('right_label')->maxLength(60),
                        Repeater::make('rows')->schema([
                            TextInput::make('topic')->required()->maxLength(80),
                            Textarea::make('left')->required()->rows(2),
                            Textarea::make('right')->required()->rows(2),
                        ])->minItems(2)->maxItems(8)->columnSpanFull(),
                        self::backgroundSelect(),
                    ])->columns(3),
                    Block::make('faq')->schema([
                        TextInput::make('eyebrow')->maxLength(80),
                        TextInput::make('heading')->required()->maxLength(180),
                        Textarea::make('intro')->rows(3),
                        Repeater::make('items')->schema([
                            TextInput::make('question')->required()->maxLength(200),
                            Textarea::make('answer')->required()->rows(4),
                        ])->minItems(1)->maxItems(20),
                        self::backgroundSelect(),
                    ]),
                    Block::make('tool_stack')->schema([
                        TextInput::make('eyebrow')->maxLength(80),
                        TextInput::make('heading')->required()->maxLength(180),
                        Textarea::make('intro')->rows(3),
                        Repeater::make('groups')->schema([
                            TextInput::make('label')->required()->maxLength(80),
                            TextInput::make('items')->required()->maxLength(255)->helperText('Comma-separated list.'),
                        ])->minItems(1)->maxItems(4)->columns(2),
                        self::backgroundSelect(),
                    ]),
                    Block::make('callout')->schema([
                        TextInput::make('heading')->required()->maxLength(180),
                        Textarea::make('text')->required()->rows(4),
                        Select::make('icon')->options(self::ICONS)->default('shield'),
                        TextInput::make('link_label')->maxLength(60),
                        TextInput::make('link_url')->maxLength(255),
                        self::backgroundSelect(),
                    ])->columns(2),
                    Block::make('latest_insights')->schema([
                        TextInput::make('eyebrow')->maxLength(80),
                        TextInput::make('heading')->required()->maxLength(180),
                        Textarea::make('intro')->rows(3)->helperText('Shows the three most recent published insights automatically.'),
                        self::backgroundSelect(),
                    ]),
                    Block::make('readiness_assessment')->schema([
                        TextInput::make('eyebrow')->maxLength(80),
                        TextInput::make('heading')->required()->maxLength(180),
                        Textarea::make('intro')->rows(3),
                        self::backgroundSelect(),
                    ]),
                    Block::make('savings_estimator')->schema([
                        TextInput::make('eyebrow')->maxLength(80),
                        TextInput::make('heading')->required()->maxLength(180),
                        Textarea::make('intro')->rows(3),
                        self::backgroundSelect(),
                    ]),
                    Block::make('risk_calculator')->schema([
                        TextInput::make('eyebrow')->maxLength(80),
                        TextInput::make('heading')->required()->maxLength(180),
                        Textarea::make('intro')->rows(3)->helperText('The AI mode appears only when an Anthropic API key is configured on the server.'),
                        self::backgroundSelect(),
                    ]),
                    Block::make('cta')->schema([
                        TextInput::make('heading')->required()->maxLength(180),
                        Textarea::make('text')->rows(3),
                        TextInput::make('button_label')->required()->maxLength(60),
                        TextInput::make('button_url')->required()->maxLength(255),
                        TextInput::make('secondary_label')->maxLength(60),
                        TextInput::make('secondary_url')->maxLength(255),
                        self::backgroundSelect(),
                    ])->columns(2),
                    Block::make('contact_form')->schema([
                        TextInput::make('heading')->required()->maxLength(180),
                        Textarea::make('text')->rows(3),
                        self::backgroundSelect(),
                    ]),
                    Block::make('waitlist_form')->schema([
                        TextInput::make('eyebrow')->maxLength(80),
                        TextInput::make('heading')->required()->maxLength(180),
                        Textarea::make('text')->rows(3),
                        self::backgroundSelect(),
                    ]),
                    Block::make('team')->schema([
                        TextInput::make('eyebrow')->maxLength(80),
                        TextInput::make('heading')->required()->maxLength(180),
                        Textarea::make('text')->rows(3),
                        self::backgroundSelect(),
                    ]),
                    Block::make('consultation_form')->schema([
                        TextInput::make('heading')->required()->maxLength(180),
                        Textarea::make('text')->rows(3),
                        self::backgroundSelect(),
                    ]),
                ])->collapsible()->cloneable()->reorderableWithButtons()->columnSpanFull(),
            ]),
            Section::make('SEO')->schema([
                TextInput::make('seo_title')->maxLength(70),
                Textarea::make('seo_description')->rows(3)->maxLength(170),
                TextInput::make('canonical_url')->url()->maxLength(255),
                TextInput::make('open_graph_title')->maxLength(95),
                Textarea::make('open_graph_description')->rows(3)->maxLength(200),
                Toggle::make('index_by_search_engines')->default(true),
                Toggle::make('robots_follow')->default(true),
            ])->columns(2)->collapsed(),
        ]);
    }

    private static function backgroundSelect(): Select
    {
        return Select::make('background')
            ->options(['dark' => 'Dark (page)', 'surface' => 'Surface', 'light' => 'Soft'])
            ->default('dark')
            ->required();
    }
}
