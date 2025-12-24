<?php

namespace App\Notifications;

use App\Helpers\NotificationModelUrl;
use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class NewUserCreated extends Notification
{
    public $user;

    public $toUserId;

    public function __construct(User $user, $toUserId)
    {
        $this->user = $user;
        $this->toUserId = $toUserId;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    public function broadcastOn()
    {
        return new PrivateChannel('admin.'.$this->toUserId);
    }

    public function broadcastWhen(): bool
    {
        return config('services.notifications_enabled');
    }

    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage(array_merge(
            $this->getNotificationData(),
            [
                'model_url' => NotificationModelUrl::getUrl(__CLASS__, $this->user->slug ?? $this->user->id),
                'notification_url' => route('admin.notifications.show', $this->id),
                'meta' => [
                    'unread_count' => $notifiable->unreadNotifications()->count(),
                ],
            ]
        ));
    }

    public function toDatabase($notifiable)
    {
        return $this->getNotificationData();

    }

    protected function getNotificationData(): array
    {
        return [
            'model_identifier' => $this->user->slug ?? $this->user->id,
            'title' => 'مستخدم جديد تم تسجيله',
            'body' => 'المستخدم '.$this->user->username.' انضم للنظام.',
            'user' => $this->user,
            'timestamp' => now()->toDateTimeString(),
        ];
    }
}
