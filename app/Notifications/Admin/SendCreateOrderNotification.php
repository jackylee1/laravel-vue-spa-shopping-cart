<?php

namespace App\Notifications\Admin;

use App\Models\Filter;
use App\Models\Setting;
use App\Models\Type;
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
        $setting = Setting::getItems();

        return (new MailMessage())->subject('Заказ на сайте ' . env('APP_NAME'))->view('emails.order', [
            'types'=> Type::types(),
            'filters' => Filter::getFilters(),
            'order' => $this->order,
            'btn_url' => url('/admin/orders/update/' . $this->order->id),
            'btn_name' => 'Просмотреть в админ панеле',
            'address' => $setting->firstWhere('slug', 'address')->value,
            'email' => $setting->firstWhere('slug', 'email')->value,
        ]);
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
