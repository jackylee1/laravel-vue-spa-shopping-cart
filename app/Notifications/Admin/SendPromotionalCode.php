<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendPromotionalCode extends Notification
{
    use Queueable;

    protected $user_name, $promotional_code;


    public function __construct($user_name, $promotional_code)
    {
        $this->user_name = $user_name;
        $this->promotional_code = $promotional_code->promotionalCode;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mail_message =  (new MailMessage)
                    ->subject('Уведомление о промокоде | '.env('APP_NAME'))
                    ->line('Администрация сайта '.env('APP_NAME').' выслала Вам данные о промокоде')
                    ->line("Промо-код: {$this->promotional_code->code}");
        if ($this->promotional_code->type === 0) {
            $mail_message->line('Тип: скидка');
            $mail_message->line("Процент скидки: {$this->promotional_code->discount}");
        }
        else {
            $mail_message->line('Тип: денежный');
            $mail_message->line("Сумма: {$this->promotional_code->cash_value} грн.");
        }

        return $mail_message;
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
