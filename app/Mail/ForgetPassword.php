<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ForgetPassword extends Mailable
{
  use Queueable, SerializesModels;

  public $token, $name;

  /**
   * Create a new message instance.
   */
  public function __construct($token, $name)
  {
    $this->token = $token;
    $this->name = $name;
  }

  /**
   * Get the message envelope.
   */
  public function envelope(): Envelope
  {
    return new Envelope(subject: 'Reset Password Link');
  }

  /**
   * Get the message content definition.
   */
  public function content(): Content
  {
    return new Content(view: 'emails.forgetPassword');
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
