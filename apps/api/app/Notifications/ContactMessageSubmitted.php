<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ContactMessageSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        private readonly string $name,
        private readonly ?string $companyName,
        private readonly string $email,
        private readonly ?string $phone,
        private readonly string $message,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New website contact message')
            ->greeting('New contact message received')
            ->line("Name: {$this->name}")
            ->line('Company: '.($this->companyName ?? '—'))
            ->line("Email: {$this->email}")
            ->line('Phone: '.($this->phone ?? '—'))
            ->line('Message:')
            ->line($this->message)
            ->replyTo($this->email, $this->name);
    }
}
