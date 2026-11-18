<?php

declare(strict_types=1);

namespace App\Services\Admin;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    public function log(
        string $action,
        string $description,
        ?Model $subject = null,
        array $properties = [],
        ?User $user = null
    ): ActivityLog {
        $actor = $user ?? auth()->user();

        return ActivityLog::create([
            'user_id' => $actor?->id,
            'action' => $action,
            'description' => $description,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject?->getKey(),
            'properties' => $properties,
            'ip_address' => Request::ip() ?? '127.0.0.1',
            'created_at' => now(),
        ]);
    }
}
