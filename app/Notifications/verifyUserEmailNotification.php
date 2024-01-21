<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;

class verifyUserEmailNotification extends VerifyEmail implements ShouldQueue
{
    use Queueable;
    public $user;

    /**
     * Create a new notification instance.
     */
    public function __construct($user = null)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        if (!$this->user) {
            $this->user = User::where('email', $notifiable->getEmailForVerification())->first();
        }
        $actionUrl = $this->verificationUrl($notifiable);
        $actionText = 'Click here to verify your email';
        return (new MailMessage)->subject('Verify your account')->view(
            'emails.user-verify',
            [
                'user' => $this->user,
                'actionText' => $actionText,
                'actionUrl' => $actionUrl,
            ]
        );
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
