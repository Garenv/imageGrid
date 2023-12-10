<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public $registeredUserData;

    /**
     * Create a new message instance.
     */
    public function __construct($registeredUserData)
    {
        $this->registeredUserData = $registeredUserData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'A New User Has Registered!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.registered-user',
            with: [
                'name' => $this->registeredUserData['name'],
                'email' => $this->registeredUserData['email'],
                'regionName' => $this->registeredUserData['regionName'],
                'cityName' => $this->registeredUserData['cityName'],
            ]
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
