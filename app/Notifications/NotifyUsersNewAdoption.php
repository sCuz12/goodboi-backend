<?php

namespace App\Notifications;

use App\Models\Dogs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NotifyUsersNewAdoption extends Notification implements ShouldQueue
{
    use Queueable;

    private $listing;

    private string $userName;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Dogs $dogListing, string $userName)
    {
        $this->listing  = $dogListing;
        $this->userName = $userName;
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
            ->greeting('Hello ' . $this->userName)
            ->subject("New goodboi for adoption")
            ->line($this->listing->name . ' is listed for adoption ')
            ->line('we are sending you this email to inform you to check this little cute dog and help him!')
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
