<?php

namespace App\Mail;

use App\Models\Feedback;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;

class FeedbackMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Feedback $feedback)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '📬 New Feedback Received — Sparktopus Tools',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.feedback',
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        if ($this->feedback->screenshot_path) {
            $fullPath = storage_path('app/public/' . $this->feedback->screenshot_path);
            if (file_exists($fullPath)) {
                $attachments[] = Attachment::fromPath($fullPath)
                    ->as('screenshot.png')
                    ->withMime('image/png');
            }
        }

        return $attachments;
    }
}
