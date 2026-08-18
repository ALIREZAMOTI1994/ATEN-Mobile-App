<?php

namespace App\Notifications;

use App\Models\Rfq;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RfqSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        /** @var Rfq $notifiable */
        $lines = $notifiable->items->map(
            fn ($item) => "- {$item->product_name} × {$item->quantity} {$item->unit}"
        )->implode("\n");

        return (new MailMessage)
            ->subject("Your ATEN RFQ {$notifiable->rfq_number} has been received")
            ->greeting("Thank you, {$notifiable->contact_name}.")
            ->line("Your request for quotation has been received and assigned tracking number {$notifiable->rfq_number}.")
            ->line('Items requested:')
            ->line($lines)
            ->line('Our sales team will contact you shortly with a formal quotation.')
            ->salutation('ATEN Industrial Connections');
    }
}
