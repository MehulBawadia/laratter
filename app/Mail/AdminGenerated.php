<?php

namespace Laratter\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminGenerated extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('services.mails.no_reply'), config('app.name'))
                    ->markdown('emails.admin_generated')
                    ->subject(config('app.name') .": Administrator Generated Successfully")
                    ->with(['user' => \Laratter\User::first()]);
    }
}
