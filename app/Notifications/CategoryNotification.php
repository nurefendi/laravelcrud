<?php

namespace App\Notifications;

use App\Models\Category;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CategoryNotification extends Notification 
{
    use Queueable;

    public $category;
    public $action;

    public function __construct(Category $category, string $action)
    {
        $this->category = $category;
        $this->action = $action;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $subject = ucfirst($this->action) . ' Category: ' . $this->category->name;

        $greeting = 'Hello ' . $notifiable->name . ',';

        $body = ucfirst($this->action) . ' category <strong>' . $this->category->name . '</strong> successfully.';

        return (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line($body);
    }
}
