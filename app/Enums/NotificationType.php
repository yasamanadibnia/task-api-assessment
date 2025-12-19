<?php

namespace App\Enums;

enum NotificationType: string
{
    case TASK_ASSIGNED = 'task_assigned';
    case TASK_UPDATED = 'task_updated';
}
