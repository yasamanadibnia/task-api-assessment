<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;

class NotificationController extends Controller
{
    use ApiResponse;

    public function __construct(
        protected NotificationService $service
    ) {}

    /**
     * fetch user unseen comments.
     */
    public function unseen(): JsonResponse
    {
        return $this->respondSuccess(
            data: $this->service->unseen()
        );
    }

    /**
     * mark user comment as seen.
     */
    public function markAsSeen(string $notificationId): JsonResponse
    {
        $this->service->markAsSeen($notificationId);

        return $this->respondSuccess(
            message: __('notification_marked_as_seen')
        );
    }
}
