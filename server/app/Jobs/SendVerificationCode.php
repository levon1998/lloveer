<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;

class SendVerificationCode implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $user;

    /**
     * Create a new job instance.
     *
     * @param $user
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job. Script to send user verification email
     *
     * @return void
     */
    public function handle()
    {
        $subject = "Please verify your email address.";
        $firstName = $this->user->first_name;
        $email = $this->user->email;

        Mail::send('email.auth.verify', [
            'lastName'         => $this->user->last_name,
            'firstName'        => $this->user->first_name,
            'verificationCode' => $this->user->token
        ],
            function($mail) use ($email, $firstName, $subject){
                $mail->from(getenv('FROM_EMAIL_ADDRESS'));
                $mail->to($email);
                $mail->subject($subject);
            });
    }
}
