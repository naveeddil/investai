<?php

namespace Tests\Feature;

use App\Enums\UserRole;
use App\Models\Page;
use App\Models\User;
use App\Policies\PagePolicy;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class PagePolicyTest extends TestCase
{
    #[DataProvider('contentRoles')]
    public function test_content_roles_can_update_pages(UserRole $role): void
    {
        $user = User::factory()->make(['role' => $role]);
        $page = Page::factory()->make();

        $this->assertTrue((new PagePolicy)->update($user, $page));
    }

    public function test_viewer_cannot_update_pages(): void
    {
        $user = User::factory()->make(['role' => UserRole::Viewer]);
        $page = Page::factory()->make();

        $this->assertFalse((new PagePolicy)->update($user, $page));
    }

    public function test_protected_page_cannot_be_deleted(): void
    {
        $user = User::factory()->make(['role' => UserRole::SuperAdmin]);
        $page = Page::factory()->make(['deletion_protected' => true]);

        $this->assertFalse((new PagePolicy)->delete($user, $page));
    }

    public static function contentRoles(): array
    {
        return [
            'super administrator' => [UserRole::SuperAdmin],
            'administrator' => [UserRole::Admin],
            'editor' => [UserRole::Editor],
        ];
    }
}
