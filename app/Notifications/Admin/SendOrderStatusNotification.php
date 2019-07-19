<?php

namespace App\Notifications\Admin;

use App\Models\Filter;
use App\Models\Setting;
use App\Models\Type;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendOrderStatusNotification extends Notification
{
    use Queueable;

    public $order_status,
           $order;

    public function __construct($order_status, $order)
    {
        $this->order_status = $order_status;
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

        $data = [
            'types'=> Type::types(),
            'filters' => Filter::getFilters(),
            'order' => $this->order,
            'new_order_status' => $this->order_status->status->name,
            'address' => $setting->firstWhere('slug', 'address')->value,
            'email' => $setting->firstWhere('slug', 'email')->value,
        ];

        if ($this->order->user_id !== null) {
            $data['btn_url'] = url('/admin/orders/update/' . $this->order->id);
            $data['btn_name'] = 'Просмотреть на сайте';
        }

        return (new MailMessage())->subject('Был изменён статус заказа | ' . env('APP_NAME'))->view('emails.order', $data);
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
