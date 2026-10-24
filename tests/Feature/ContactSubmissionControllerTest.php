<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ContactSubmissionControllerTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_valid_enquiry_is_stored_and_redirects_back(): void
    {
        $response = $this->from('/contact')->post('/contact', [
            'name' => 'Amina Rahimi',
            'email' => 'amina@example.com',
            'company' => 'Example Ltd',
            'enquiry_type' => 'partnership',
            'message' => 'I would like to discuss a partnership opportunity.',
        ]);

        $response->assertRedirect('/contact');
        $response->assertSessionHas('contact_success');
        $this->assertDatabaseHas('contact_submissions', [
            'email' => 'amina@example.com',
            'status' => 'new',
        ]);
    }

    public function test_invalid_enquiry_is_rejected_without_storing_it(): void
    {
        $response = $this->from('/contact')->post('/contact', [
            'name' => '',
            'email' => 'not-an-email',
            'enquiry_type' => 'unsupported',
            'message' => 'short',
        ]);

        $response->assertRedirect('/contact');
        $response->assertSessionHasErrors(['name', 'email', 'enquiry_type', 'message']);
        $this->assertDatabaseCount('contact_submissions', 0);
    }
}
