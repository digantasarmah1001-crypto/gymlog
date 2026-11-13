<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * Sent once, right after someone registers.
 * With MAIL_MAILER=log (default in .env) the email is written to storage/logs/laravel.log
 * instead of really being sent; set MAIL_* in .env to use a real mail service.
 */
class WelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public User $user)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Welcome to GymLog, '.$this->user->name.'!');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.welcome');
    }
}
