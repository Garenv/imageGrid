<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
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
            from: $this->emailData['from']
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
                'name' => $this->emailData['from'],
                'messageText' => $this->emailData['messageText']
            ]
        );
    }


//$emailData = [
//'to' => 'phopixelmain@gmail.com',
//'from' => Auth::user()['email'],
//'subject' => $subject,
//'html' => htmlEmail('emails.support.support', [
//'name' => Auth::user()['name'],
//'messageText' => $messageText
//]),
//'attachment' => [
//['filePath' => $fullFilePath, 'filename' => $fileName]
//]
//];

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [
            Attachment::fromPath($this->emailData['attachment']['filePath'])
        ];
    }
}
