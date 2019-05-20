<?php

namespace App\Notifications\PublicNotification;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendCreateOrderNotification extends Notification
{
    use Queueable;

    public $order;

    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $mail_message = new MailMessage();
        $mail_message->subject('Заказ успешно создан | '.env('APP_NAME'))
            ->greeting('Заказ был успешно создан.')
            ->line('С Вами в ближайшее время свяжется менеджер для подствержение заказа.')
            ->line("ID Заказа: {$this->order->id}");
        
        if (auth()->check()) {
            $mail_message->action('Просмотреть в личном кабинете', url()->to("/user/order/{$this->order->id}"));
        }

        return $mail_message;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
