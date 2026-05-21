<?php

namespace App\Providers;

use App\Events\LiveSessionBookingCreated;
use App\Events\PaymentSucceeded;
use App\Listeners\CreateLiveSessionBooking;
use App\Listeners\UnlockLiveSessionAccess;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

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
        PaymentSucceeded::class => [
            CreateLiveSessionBooking::class,
        ],
        LiveSessionBookingCreated::class => [
            UnlockLiveSessionAccess::class,
        ],
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
