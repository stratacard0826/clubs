<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        'App\Events\LoginEvent' => ['App\Listeners\LoginListener'],
        'App\Events\ActivityEvent' => ['App\Listeners\ActivityListener'],
        'App\Events\EventCreationEvent' => ['App\Listeners\EventCreationListener'],
        'App\Events\EventCancelledEvent' => ['App\Listeners\EventCancelledListener'],
        'App\Events\EventActiveEvent' => ['App\Listeners\EventActiveListener'],
        'App\Events\NewChatEvent' => ['App\Listeners\NewChatListener'],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        //
    }
}
