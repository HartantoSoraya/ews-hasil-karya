<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ClientRegisted extends Notification
{
    use Queueable;

    public string $name;
    public string $email;
    public string $password;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $name, string $email, string $password)
    {
        $this->name = $name;
        $this->email = $email;
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Hallo ' . $this->name . ', Terima kasih telah mendaftar di aplikasi kami')
            ->line('Berikut adalah informasi akun anda:')
            ->line('Email: ' . $this->email)
            ->line('Password: ' . $this->password)
            ->line('Silahkan login di aplikasi kami dengan menggunakan email dan password diatas.');

    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
