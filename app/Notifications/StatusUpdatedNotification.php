<?php

namespace App\Notifications;

use App\Mail\StatusUpdatedMail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StatusUpdatedNotification extends Notification
{
    use Queueable;

    public $name;
    public $topic;
    public $status;
    public $email;

    /**
     * Create a new notification instance.
     */
    public function __construct($name, $topic, $status, $email)
    {
        $this->name = $name;
        $this->topic = $topic;
        $this->status = $status;
        $this->email = $email;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        return (new StatusUpdatedMail($this->name, $this->topic, $this->status))
            ->to($this->email);
    }
}
