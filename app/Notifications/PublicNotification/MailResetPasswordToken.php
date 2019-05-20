<?php

namespace App\Notifications\PublicNotification;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class MailResetPasswordToken extends Notification
{
    use Queueable;

    protected $token, $user;


    public function __construct($token, User $user) {
        $this->token = $token;
        $this->user = $user;
    }

    public function via($notifiable) {
        return ['mail'];
    }

    public function toMail($notifiable) {
        return (new MailMessage)
            ->subject('Восстановление пароля | ' . env('APP_NAME'))
            ->greeting('Восстановление пароля')
            ->line('Похоже Вы забыли пароль.')
            ->action('Сбросить пароль', url('/reset_password', [
                'token' => $this->token,
                'email' => $this->user->email
            ]));
    }

    public function toArray($notifiable) {
        return [//
        ];
    }
}