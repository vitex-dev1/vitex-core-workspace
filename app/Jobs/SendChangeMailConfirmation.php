<?php

namespace App\Jobs;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Lang;
use Mail;

class SendChangeMailConfirmation extends Job implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /** @var \App\Models\User $user */
    protected $user;
    protected $template;

    /**
     * Create a new job instance.
     *
     * @param  User  $user
     * @return void
     */
    public function __construct(User $user, string $template = 'auth.emails.change_email')
    {
        $this->user = $user;

        if($this->user->platform == User::PLATFORM_BACKOFFICE) {
            $this->template = 'admin.'.$template;
        } else {
            $this->template = 'client.'.$template;
        }
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::send($this->template, ['user' => $this->user], function ($m) {
            $m->from(config('mail.from.address'), config('mail.from.name'));
            $m->to($this->user->email_tmp, $this->user->name)
                ->subject(Lang::get('mail.user.subject_confirm_change_email'));
        });
    }
}
