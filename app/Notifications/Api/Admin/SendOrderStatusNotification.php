<?php

namespace App\Notifications\Api\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendOrderStatusNotification extends Notification
{
    use Queueable;

    public $order_status,
           $order_id;

    public function __construct($order_status, $order_id)
    {
        $this->order_status = $order_status;
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
        return (new MailMessage)
                    ->subject('Был изменён статус заказа')
                    ->greeting('Был изменён статус заказа')
                    ->line("ID Заказа: $this->order_id")
                    ->line('Текущий статус заказа: ' . $this->order_status->status->name);
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
