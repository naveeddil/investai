<?php

namespace App\Filament\Pages;

use App\Models\SiteSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class WebsiteSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Website settings';

    protected static ?string $title = 'Website settings';

    protected string $view = 'filament.pages.website-settings';

    public ?array $data = [];

    public function mount(): void
    {
        $keys = ['brand.name', 'brand.tagline', 'brand.logo', 'brand.favicon', 'seo.default.description', 'footer.description', 'appearance.theme', 'menu.services.label', 'menu.approach.label', 'menu.tools.label', 'menu.blog.label', 'menu.about.label', 'menu.show.services', 'menu.show.approach', 'menu.show.tools', 'menu.show.blog', 'menu.show.about', 'menu.cta.label', 'email.provider', 'email.host', 'email.port', 'email.username', 'email.from.address', 'email.from.name', 'email.encryption', 'mailchimp.audience.id'];
        $values = [];
        foreach ($keys as $key) {
            $values[str_replace('.', '_', $key)] = SiteSetting::read($key);
        }
        $this->form->fill($values);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->statePath('data')->components([
            Section::make('Brand and SEO')->schema([
                TextInput::make('brand_name')->label('Website name')->default('InvestAI')->required(),
                TextInput::make('brand_tagline')->label('Short tagline')->maxLength(120),
                Textarea::make('seo_default_description')->label('Default SEO description')->rows(3)->maxLength(170),
                Textarea::make('footer_description')->label('Footer description')->rows(3),
                FileUpload::make('brand_logo')->label('Logo')->disk('public')->directory('branding')->image(),
                FileUpload::make('brand_favicon')->label('Favicon')->disk('public')->directory('branding')->acceptedFileTypes(['image/png', 'image/x-icon', 'image/vnd.microsoft.icon']),
            ])->columns(2),
            Section::make('Theme and menu')->schema([
                Select::make('appearance_theme')->label('Default theme')->options(['ivory' => 'Ivory', 'midnight' => 'Midnight', 'cobalt' => 'Cobalt'])->default('ivory')->required(),
                TextInput::make('menu_services_label')->default('Services'), Toggle::make('menu_show_services')->default(true),
                TextInput::make('menu_approach_label')->default('How it works'), Toggle::make('menu_show_approach')->default(true),
                TextInput::make('menu_tools_label')->default('Risk & calculators'), Toggle::make('menu_show_tools')->default(true),
                TextInput::make('menu_blog_label')->default('Blog'), Toggle::make('menu_show_blog')->default(true),
                TextInput::make('menu_about_label')->default('About'), Toggle::make('menu_show_about')->default(true),
                TextInput::make('menu_cta_label')->label('Primary button label')->default('Consult'),
            ])->columns(2),
            Section::make('Email delivery')->description('Brevo and other SMTP providers work directly. Mailchimp marketing uses its API and audience ID; storing those credentials does not enable SMTP delivery.')->schema([
                Select::make('email_provider')->options(['log' => 'Disabled / log only', 'smtp' => 'Custom SMTP', 'brevo' => 'Brevo SMTP', 'mailchimp' => 'Mailchimp audience (API)'])->default('log')->required(),
                TextInput::make('email_host')->placeholder('smtp-relay.brevo.com'), TextInput::make('email_port')->numeric()->default(587),
                TextInput::make('email_username'), TextInput::make('email_password')->password()->revealable()->dehydrated(fn ($state) => filled($state))->helperText('Leave blank to keep the saved password.'),
                Select::make('email_encryption')->options(['tls' => 'TLS', 'ssl' => 'SSL', 'none' => 'None'])->default('tls'),
                TextInput::make('email_from_address')->email(), TextInput::make('email_from_name')->default('InvestAI'),
                TextInput::make('mailchimp_api_key')->password()->revealable()->dehydrated(fn ($state) => filled($state))->helperText('Stored encrypted. Needed only for a future Mailchimp audience sync.'),
                TextInput::make('mailchimp_audience_id')->label('Mailchimp audience ID'),
            ])->columns(2),
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();
        foreach ($data as $field => $value) {
            if (in_array($field, ['email_password', 'mailchimp_api_key'], true)) {
                continue;
            }
            SiteSetting::write(str_replace('_', '.', $field), $value, str_starts_with($field, 'email_') || str_starts_with($field, 'mailchimp_') ? 'email' : 'website', is_bool($value) ? 'boolean' : 'string');
        }
        SiteSetting::writeSecret('email.password', $data['email_password'] ?? null);
        SiteSetting::writeSecret('mailchimp.api_key', $data['mailchimp_api_key'] ?? null);
        Notification::make()->success()->title('Website settings saved')->send();
    }

    public function getFormActions(): array
    {
        return [Action::make('save')->label('Save settings')->submit('save')];
    }
}
