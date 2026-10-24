<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class WaitlistSignupControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_consenting_signup_is_stored(): void
    {
        $response = $this->from('/')->post('/waitlist', [
            'name' => 'Omar Khan',
            'email' => 'omar@example.com',
            'audience_type' => 'individual',
            'consent' => '1',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHas('waitlist_success');
        $this->assertDatabaseHas('waitlist_signups', [
            'email' => 'omar@example.com',
            'consent' => true,
            'status' => 'active',
        ]);
    }

    public function test_signup_without_consent_is_rejected(): void
    {
        $response = $this->from('/')->post('/waitlist', [
            'name' => 'Omar Khan',
            'email' => 'omar@example.com',
            'audience_type' => 'individual',
        ]);

        $response->assertRedirect('/');
        $response->assertSessionHasErrors(['consent']);
        $this->assertDatabaseCount('waitlist_signups', 0);
    }
}
