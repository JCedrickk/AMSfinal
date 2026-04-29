<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class IdRequestApprovedMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $idRequest;

    public function __construct($user, $idRequest)
    {
        $this->user = $user;
        $this->idRequest = $idRequest;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Alumni ID Request Has Been Approved!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.id-request-approved',
        );
    }
}