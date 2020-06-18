<?php

namespace Laratter\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UserRegistered extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    /**
     * Get the registered user.
     *
     * @param \Laratter\User $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from(config('services.mails.no_reply'), config('app.name'))
                    ->markdown('emails.user_registered')
                    ->subject(config('app.name') ." Registration successfull.")
                    ->with(['user' => $this->user]);
    }
}
