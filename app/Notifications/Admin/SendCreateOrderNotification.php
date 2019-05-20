<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendCreateOrderNotification extends Notification
{
    use Queueable;

    public $order_id;

    public function __construct($order_id)
    {
        $this->order_id = $order_id;
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
        $mail_message->subject('У вас новый заказ | '.env('APP_NAME'))
            ->greeting('У вас новый заказ.');
        $mail_message->action('Просмотреть в админ. панеле', url("/admin/orders/update/{$this->order_id}"));

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
