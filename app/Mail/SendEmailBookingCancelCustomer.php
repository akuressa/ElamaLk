<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendEmailBookingCancelCustomer extends Mailable
{
    use Queueable, SerializesModels;

    // Variables
    public $details_customer;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($details_customer)
    {
        $this->details_customer = $details_customer;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.email-booking-cancel-customer');
    }
}
