<?php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogService
{
    public function log(string $action, string $entityType, ?int $entityId, string $description): void
    {
        try {
            ActivityLog::create([
                'user_id' => auth()->id(),
                'action' => $action,
                'entity_type' => $entityType,
                'entity_id' => $entityId,
                'description' => $description
            ]);
        } catch (\Exception $e) {

            logger()->error(
                'Failed create activity log',
                [
                    'message'
                    => $e->getMessage()
                ]
            );
        }
    }
}
