<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Enums\UserStatus;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class AdminAccessTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_unauthenticated_user_is_redirected_to_admin_login(): void
    {
        $this->get('/admin')->assertRedirect('/admin/login');
    }

    public function test_active_user_can_access_admin_panel(): void
    {
        $user = User::factory()->create();

        $this->assertTrue($user->canAccessPanel(Filament::getPanel('admin')));
        $this->actingAs($user)->get('/admin/pages')->assertOk();
    }

    public function test_admin_header_links_to_the_public_website(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/admin')
            ->assertOk()
            ->assertSee('Visit website')
            ->assertSee('href="'.url('/').'"', false);
    }

    public function test_deactivated_user_cannot_access_admin_panel(): void
    {
        $user = User::factory()->make(['status' => UserStatus::Deactivated]);

        $this->assertFalse($user->canAccessPanel(Filament::getPanel('admin')));
    }

    public function test_administrator_can_render_page_builder(): void
    {
        $user = User::factory()->withRole(UserRole::Admin)->create();

        $this->actingAs($user)->get('/admin/pages/create')->assertOk();
    }

    public function test_administrator_can_render_website_settings(): void
    {
        $this->actingAs(User::factory()->withRole(UserRole::Admin)->create())
            ->get('/admin/website-settings')
            ->assertOk()
            ->assertSee('Brand and SEO')
            ->assertSee('Email delivery');
    }
}
