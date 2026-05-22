<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Spatie\Permission\Events\PermissionAttachedEvent;
use Spatie\Permission\Events\PermissionDetachedEvent;
use Spatie\Permission\Events\RoleAttachedEvent;
use Spatie\Permission\Events\RoleDetachedEvent;
use App\Listeners\PermissionChangeAuditListener;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PermissionAttachedEvent::class => [
            PermissionChangeAuditListener::class,
        ],
        PermissionDetachedEvent::class => [
            PermissionChangeAuditListener::class,
        ],
        RoleAttachedEvent::class => [
            PermissionChangeAuditListener::class,
        ],
        RoleDetachedEvent::class => [
            PermissionChangeAuditListener::class,
        ],
    ];
}