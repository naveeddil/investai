<?php

namespace App\Mail;

use App\Models\NewsletterSubscriber;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class ConfirmNewsletterSubscription extends Mailable
{
    public function __construct(public NewsletterSubscriber $subscriber, public string $confirmationToken, public string $manageToken) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Confirm your InvestAI email subscription');
    }

    public function content(): Content
    {
        return new Content(view: 'mail.newsletter-confirmation');
    }
}
