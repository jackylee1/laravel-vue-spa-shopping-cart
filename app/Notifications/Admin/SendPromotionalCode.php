<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendPromotionalCode extends Notification
{
    use Queueable;

    protected $user_name, $promotional_code, $promotional_discount;


    public function __construct($user_name, $promotional_code, $promotional_discount)
    {
        $this->user_name = $user_name;
        $this->promotional_code = $promotional_code;
        $this->promotional_discount = $promotional_discount;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Уведомление о промокоде | '.env('APP_NAME'))
                    ->line('Администрация сайта '.env('APP_NAME').' выслала Вам данные о промокоде')
                    ->line("Промо-код: {$this->promotional_code}")
                    ->line("Процент скидки: {$this->promotional_discount}");
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
