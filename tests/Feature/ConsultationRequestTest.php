<?php

namespace Tests\Feature;

use App\Models\ConsultationRequest;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Tests\TestCase;

class ConsultationRequestTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_a_valid_consultation_request_is_stored(): void
    {
        $response = $this->post('/consultation', [
            'name' => 'Amina Example',
            'email' => 'amina@example.com',
            'preferred_contact' => 'email',
            'topic' => 'research',
            'message' => 'Please contact me about your research approach.',
            'consent' => '1',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('consultation_success');
        $this->assertDatabaseHas(ConsultationRequest::class, [
            'email' => 'amina@example.com',
            'topic' => 'research',
            'status' => 'new',
        ]);
    }

    public function test_consent_and_a_valid_email_are_required(): void
    {
        $this->from('/consultation')->post('/consultation', [
            'name' => 'Amina Example',
            'email' => 'not-an-email',
            'preferred_contact' => 'email',
            'topic' => 'general',
        ])->assertSessionHasErrors(['email', 'consent']);

        $this->assertDatabaseCount(ConsultationRequest::class, 0);
    }
}
