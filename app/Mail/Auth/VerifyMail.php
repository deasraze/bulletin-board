<?php

namespace App\Mail\Auth;

use App\Entity\User\User;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerifyMail extends Mailable
{
    use SerializesModels;

    public User $user;

    /**
     * Create a new message instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): VerifyMail
    {
        return $this
            ->subject('Signup Confirmation')
            ->markdown('emails.auth.register.verify');
    }
}
