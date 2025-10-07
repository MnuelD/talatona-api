<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TwoFactorLogin extends Mailable
{
    use Queueable, SerializesModels;
    public string $link; //

    /**
     * Create a new message instance.
     */
     public function __construct(string $link)
    {
        $this->link = $link; //
    }

    public function build()
    {
        return $this->subject('Verificação de dois fatores')
                    ->view('emails.2fa')
                    ->with(['link' => $this->link]);
    }
    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Two Factor Login',
        );
    }

    /**
     * Get the message content definition.
     */


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
