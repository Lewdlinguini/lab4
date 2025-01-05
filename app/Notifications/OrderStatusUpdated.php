<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderStatusUpdated extends Notification
{
    use Queueable;

    public $order;

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Your Order Status Has Been Updated')
                    ->line('The status of your order has been updated.')
                    ->line('Order ID: ' . $this->order->id)
                    ->line('Current Status: ' . $this->order->shipping_status)
                    ->action('View Order', url('/orders/' . $this->order->id));
    }

}