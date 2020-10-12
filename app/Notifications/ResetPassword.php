<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Notifications\Messages\MailMessage;

/**
 * Overwrite this method for include email to Reset password link in the Email
 * Class ResetPassword
 * @package App\Notifications
 */
class ResetPassword extends BaseResetPassword
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $routeName = 'password.reset';

        if($notifiable->platform == User::PLATFORM_BACKOFFICE) {
            $routeName = 'admin.' . $routeName;
        }

        $link = route($routeName, [$this->token, 'email' => $notifiable->email]);

        return (new MailMessage)
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->action('Reset Password', $link)
            ->line('If you did not request a password reset, no further action is required.');
    }
}
