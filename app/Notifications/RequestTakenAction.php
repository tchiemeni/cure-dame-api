<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\DiscussionRequest;

class RequestTakenAction extends Notification
{
    use Queueable;

    public DiscussionRequest $request;

    public function __construct(DiscussionRequest $request)
    {
        $this->request = $request;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Votre Requête de Discussion a été prise en charge 🙏')
                    ->greeting('Chère/Cher ' . $notifiable->name . ',')
                    ->line('Nous vous confirmons que votre requête de discussion a été reçue et est maintenant en cours de traitement par notre équipe spirituelle.')
                    ->line('**Sujet de la requête :** ' . $this->request->subject)
                    ->line('Un membre de l\'équipe vous contactera sous peu pour arranger l\'échange ou répondre directement à votre préoccupation.')
                    ->action('Voir vos Requêtes', url('/dashboard/requests')) // Adaptez l'URL
                    ->line('Soyez béni(e) et restez dans la prière.')
                    ->salutation('L\'équipe "CURE D\'ÂME 2025"');
    }
}
