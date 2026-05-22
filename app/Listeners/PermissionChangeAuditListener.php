<?php

namespace App\Listeners;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use OwenIt\Auditing\Models\Audit;
use Spatie\Permission\Contracts\Permission;
use Spatie\Permission\Contracts\Role;

class PermissionChangeAuditListener
{
    public function handle(object $event): void
    {
        $model = $event->model;
        $reason = request()->input('reason');

        $items = $this->resolveNames($event->permissionsOrIds ?? $event->rolesOrIds ?? []);

        Audit::create([
            'user_type' => auth()->check() ? get_class(auth()->user()) : null,
            'user_id' => Auth::id(),
            'event' => class_basename($event),
            'auditable_type' => get_class($model),
            'auditable_id' => $model->getKey(),
            'old_values' => null,
            'new_values' => json_encode(['items' => $items], JSON_THROW_ON_ERROR),
            'url' => request()->fullUrl(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'reason' => $reason,
        ]);
    }

    private function resolveNames(mixed $items): array
    {
        if ($items instanceof Permission || $items instanceof Role) {
            return [$items->name];
        }

        if ($items instanceof Collection) {
            return $items->map(fn ($i) => $i->name ?? $i)->values()->all();
        }

        return array_map(static fn ($i) => $i instanceof Permission || $i instanceof Role ? $i->name : $i, (array) $items);
    }
}