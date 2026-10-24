<?php

namespace Tests\Feature;

use App\Mail\ConfirmNewsletterSubscription;
use App\Models\NewsletterSubscriber;
use App\Models\TeamMember;
use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class NewsletterSubscriptionTest extends TestCase
{
    use LazilyRefreshDatabase;

    public function test_a_visitor_can_request_and_confirm_selected_emails(): void
    {
        Mail::fake();
        $this->post('/newsletter/subscribe', ['name' => 'Sam', 'email' => 'SAM@example.com', 'monthly_newsletter' => '1', 'weekly_tips' => '1', 'consent' => '1'])->assertSessionHas('newsletter_success');
        $subscriber = NewsletterSubscriber::firstOrFail();
        $this->assertSame('sam@example.com', $subscriber->email);
        $this->assertTrue($subscriber->weekly_tips);
        $this->assertSame('pending', $subscriber->status);
        Mail::assertSent(ConfirmNewsletterSubscription::class);
        $mail = Mail::sent(ConfirmNewsletterSubscription::class)->first();
        $this->get(route('newsletter.confirm', $mail->confirmationToken))->assertOk()->assertSee('Subscription confirmed');
        $this->assertSame('active', $subscriber->fresh()->status);
    }

    public function test_about_page_renders_visible_team_members(): void
    {
        TeamMember::factory()->create(['name' => 'Visible Colleague', 'is_visible' => true]);
        $this->get('/about')->assertOk()->assertSee('Meet the team.')->assertSee('Visible Colleague');
    }
}
