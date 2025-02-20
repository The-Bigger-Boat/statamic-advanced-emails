<?php

namespace TheBiggerBoat\StatamicAdvancedEmails;

use Statamic\Providers\AddonServiceProvider;
use Statamic\Facades\CP\Nav;

class ServiceProvider extends AddonServiceProvider
{
    protected $listen = [
        'Statamic\Events\FormSubmitted' => [
            'TheBiggerBoat\StatamicAdvancedEmails\Listeners\SendAdvancedEmail',
        ]
    ];

    protected $routes = [
        'cp' => __DIR__.'/../routes/cp.php',
    ];

    public function bootAddon()
    {
        $this->bootNavigation();
    }

    public function bootNavigation(): void
    {
        Nav::extend(function ($nav) {
            $nav->tools('Advanced Emails')
                ->can('configure form advanced emails')
                ->route('advanced-emails.index')
                ->icon('form');
        });
    }
}
