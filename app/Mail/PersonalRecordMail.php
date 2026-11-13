<?php

namespace App\Mail;

use App\Models\WorkoutSet;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

/**
 * "New personal record!" email, sent by the NotifyPersonalRecord job.
 */
class PersonalRecordMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public WorkoutSet $set)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'New personal record: '.$this->set->exercise->name.'!');
    }

    public function content(): Content
    {
        return new Content(view: 'emails.personal-record');
    }
}
