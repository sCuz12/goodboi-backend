<?php

namespace App\Notifications;

use App\Models\Dogs;
use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyFoundDogLostDogOwners extends Notification implements ShouldQueue
{
    use Queueable;
    private $listing;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Dogs $dogListing)
    {
        $this->listing = $dogListing;
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
        return (new MailMessage)
            ->subject("A Goodboi hero found dog in your city !")
            ->line('A Goodboi hero found dog in your city')
            ->line('we are sending you this email to inform you to check if this dog is your missing dog ')
            ->action('SEE THE DOG', url(env('CLIENT_URL') . "/listings/found-dogs/view/" . $this->listing->id))
            ->line('Thank you for using Goodboi!');
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
