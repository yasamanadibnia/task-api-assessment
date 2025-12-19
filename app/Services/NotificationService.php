<?php

namespace App\Services;

use App\Enums\NotificationType;
use App\Models\User;
use App\Notifications\TaskNotification;
use Illuminate\Support\Collection;

class NotificationService
{
    public function notifyTask(
        User $user,
        NotificationType $type,
        int $taskId,
        string $message
    ): void {
        $user->notify(
            new TaskNotification($type, $taskId, $message)
        );
    }

    public function unseen(): Collection
    {
        return auth()->user()->unreadNotifications;
    }

    public function markAsSeen(string $notificationId): void
    {
        auth()->user()
            ->notifications()
            ->where('id', $notificationId)
            ->firstOrFail()
            ->markAsRead();
    }
}
