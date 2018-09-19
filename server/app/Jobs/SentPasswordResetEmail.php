<?php

namespace App\Jobs;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SentPasswordResetEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $email;

    /**
     * Create a new job instance.
     *
     * @param $email
     */
    public function __construct($email)
    {
        $this->email = $email['email'];
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email     = $this->email;
        $resetCode = str_random(30);
        $subject   = "Your Password Reset Link";

        $isSend = DB::table('password_resets')->where('email', $email)->first();

        if (!is_null($isSend)) {
            DB::table('password_resets')->update([
                'token'      => $resetCode
            ]);
        } else {
            DB::table('password_resets')->insert([
                'email'      => $email,
                'token'      => $resetCode,
                'created_at' => Carbon::now()
            ]);
        }


        Mail::send('email.auth.resetPass', [ 'resetCode' => $resetCode ],
            function($mail) use ($email, $subject){
                $mail->from(getenv('FROM_EMAIL_ADDRESS'));
                $mail->to($email);
                $mail->subject($subject);
            });
    }
}
