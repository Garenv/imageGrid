<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class Support extends Mailable
{
    use Queueable, SerializesModels;

    public $emailData;
    /**
     * Create a new message instance.
     */
    public function __construct($emailData)
    {
        $this->emailData = $emailData;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailData['subject'],
            replyTo: $this->emailData['from']
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.support.support',
            with: [
                'name' => $this->emailData['name'],
                'messageText' => $this->emailData['messageText'],
                'userId' => $this->emailData['UserID']
            ]
        );
    }

    /**
     * @return array
     */
    public function attachments(): array
    {
        $hasAttachment = isset($this->emailData['attachment']['filePath']);

        $filePathNotEmpty = $hasAttachment && $this->emailData['attachment']['filePath'] !== "";

        return $filePathNotEmpty ? [Attachment::fromPath($this->emailData['attachment']['filePath'])] : [];
    }


}
