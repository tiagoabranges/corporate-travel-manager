<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\TravelOrder;

class TravelOrderStatusNotification extends Notification
{
    use Queueable;

    protected $order;

    public function __construct(TravelOrder $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail']; // pode mudar para ['database'] se quiser salvar no banco
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Atualização do Pedido de Viagem')
            ->line('Seu pedido de viagem foi atualizado.')
            ->line('Destino: ' . $this->order->destination)
            ->line('Status: ' . $this->order->status)
            ->line('Obrigado por utilizar o sistema.');
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'status' => $this->order->status,
            'destination' => $this->order->destination
        ];
    }
}
