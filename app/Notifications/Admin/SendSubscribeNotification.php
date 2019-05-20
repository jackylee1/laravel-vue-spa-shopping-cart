<?php

namespace App\Notifications\Admin;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendSubscribeNotification extends Notification
{
    use Queueable;

    public $subscribe_id;

    public function __construct($subscribe_id)
    {
        $this->subscribe_id = $subscribe_id;
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
        $mail_message->subject('У вас новая подписка | '.env('APP_NAME'))
            ->greeting('У вас новая подписка.');
        $mail_message->action('Просмотреть в админ. панеле', url("/admin/subscribes/{$this->subscribe_id}"));

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
