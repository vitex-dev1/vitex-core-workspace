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

class SendAdminPasswordEmail extends Job implements ShouldQueue
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
    public function __construct(User $user, string $template = 'admin.auth.emails.send_password')
    {
        $this->user = $user;
        $this->template = $template;
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
            $m->to($this->user->email, $this->user->name)
                ->subject(Lang::get('mail.user.subject_create_admin'));
        });
    }
}
