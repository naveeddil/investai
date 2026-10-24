<?php

namespace App\Mail;

use App\Models\Article;
use App\Models\NewsletterSubscriber;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;

class WeeklyTipMail extends Mailable
{
    public function __construct(public NewsletterSubscriber $subscriber, public Article $article) {}

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'InvestAI weekly tip: '.$this->article->title);
    }

    public function content(): Content
    {
        return new Content(view: 'mail.weekly-tip');
    }
}
