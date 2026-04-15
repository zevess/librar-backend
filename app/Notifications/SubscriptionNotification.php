<?php

namespace App\Notifications;

use App\Models\Book;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private readonly Book $book)
    {
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Книга снова доступна ' . config('app.name'))
            ->view('emails.subscription-notification', [
                'bookTitle' => $this->book->title,
                'bookUrl' => $this->bookUrl($notifiable)
            ]);
    }

    public function toDatabase(object $notifiable): array
    {
        return [
            'type' => 'book',
            'id' => $this->book->id,
            'slug' => $this->book->slug,
            'title' => $this->book->title,
            'image' => $this->book->image
        ];
    }

    public function bookUrl(object $notifiable)
    {
        $frontendUrl = env('VITE_APP_URL');
        return url($frontendUrl . '/book/' . $this->book->slug . '-' . $this->book->id);
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
