<?php

namespace Mixxbv\SocialiteEventgateway;

use Illuminate\Contracts\Events\Dispatcher;
use Illuminate\Support\ServiceProvider;
use SocialiteProviders\Manager\SocialiteWasCalled;

class SocialiteEventgatewayServiceProvider extends ServiceProvider
{
    public function boot(Dispatcher $events): void
    {
        $events->listen(SocialiteWasCalled::class, [EventGatewayExtendSocialite::class, 'handle']);
    }
}
