<?php

namespace Mixxbv\SocialiteEventgateway;

use SocialiteProviders\Manager\SocialiteWasCalled;

class EventGatewayExtendSocialite
{
    /**
     * Register the provider with Socialite.
     */
    public function handle(SocialiteWasCalled $socialiteWasCalled): void
    {
        $socialiteWasCalled->extendSocialite('event-gateway', Provider::class);
    }
}