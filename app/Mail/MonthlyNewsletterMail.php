<?php

namespace App\Mail;

use App\Models\NewsletterSubscriber;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Support\Collection;

class MonthlyNewsletterMail extends Mailable
{
    public function __construct(public NewsletterSubscriber $subscriber, public Collection $articles, public string $monthLabel) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'InvestAI monthly newsletter — '.$this->monthLabel);
    }

    public function content(): Content
    {
        return new Content(view: 'mail.monthly-newsletter');
    }
}
