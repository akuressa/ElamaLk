<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailPlanAdmin extends Mailable
{
    use Queueable, SerializesModels;

    // Variables
    public $details_admin;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details_admin)
    {
        $this->details_admin = $details_admin;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.email-plan-admin');
    }
}
