<?php

namespace App\Http\Controllers;

use App\Services\NotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class NotificationController extends Controller
{
    public function __construct(
        protected NotificationService $service
    ) {}

    /**
     * fetch user unseen comments.
     */
    public function unseen(): JsonResponse
    {
        return Response::success(
            data: $this->service->unseen()
        );
    }

    /**
     * mark user comment as seen.
     */
    public function markAsSeen(string $id): JsonResponse
    {
        $this->service->markAsSeen($id);

        return Response::success(
            message: __('notification_marked_as_seen')
        );
    }
}
