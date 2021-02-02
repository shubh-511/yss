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
         'App\Events\UserRegisterEvent' => [
            'App\Listeners\OtpSendListner',
        ],
        'App\Events\ForgotPasswordEvent' => [
            'App\Listeners\ForgotOtpListner',
        ],
        'App\Events\WelcomeUserEvent' => [
            'App\Listeners\WelcomeListner',
        ],
        'App\Events\BookingEvent' => [
            'App\Listeners\SuccessfulBookingListner',
        ],
        'App\Events\ProfileCompleteEvent' => [
            'App\Listeners\ProfileCompleteListner',
        ],
        'App\Events\BookingCounsellorEvent' => [
            'App\Listeners\BookingCounsellorListner',
        ],
        'App\Events\ResetPasswordEvent' => [
            'App\Listeners\ResetPasswordListner',
        ],
        'App\Events\FailedBookingEvent' => [
            'App\Listeners\FailedBookingListner',
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
