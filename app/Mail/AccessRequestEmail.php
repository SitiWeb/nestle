<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AccessRequestEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $validatedData;

    public function __construct($validatedData)
    {
        $this->validatedData = $validatedData;
    }

    public function build()
    {
        return $this->subject('Access Request')
                    ->view('emails.access_request');
    }
}
