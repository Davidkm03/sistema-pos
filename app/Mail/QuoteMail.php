<?php

namespace App\Mail;

use App\Models\Quote;
use App\Models\BusinessSetting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuoteMail extends Mailable
{
    use Queueable, SerializesModels;

    public Quote $quote;
    public BusinessSetting $businessSettings;

    /**
     * Create a new message instance.
     */
    public function __construct(Quote $quote, BusinessSetting $businessSettings)
    {
        $this->quote = $quote;
        $this->businessSettings = $businessSettings;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Cotización #' . $this->quote->quote_number . ' - ' . $this->businessSettings->business_name,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            markdown: 'emails.quote',
            with: [
                'quote' => $this->quote,
                'businessSettings' => $this->businessSettings,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
