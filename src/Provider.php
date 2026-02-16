<?php

namespace Mixxbv\SocialiteEventgateway;

use SocialiteProviders\Manager\OAuth2\AbstractProvider;
use SocialiteProviders\Manager\OAuth2\User;

class Provider extends AbstractProvider
{
    /**
     * If your SSO requires extra scopes, put defaults here.
     */
    protected $scopes = [];

    /**
     * Optional: if your provider uses space-separated scopes, keep default.
     * Otherwise override: protected $scopeSeparator = ',';
     */

    /**
     * Authorization endpoint.
     */
    protected function getAuthUrl($state): string
    {
        // services.php should contain 'base_url' or explicit auth URL.
        $authorizeUrl = rtrim($this->baseUrl(), '/').'/oauth/authorize';
        return $this->buildAuthUrlFromBase($authorizeUrl, $state);
    }

    /**
     * Token endpoint.
     */
    protected function getTokenUrl(): string
    {
        return rtrim($this->baseUrl(), '/').'/oauth/token';
    }

    /**
     * Userinfo endpoint (OIDC often uses /oauth/userinfo or /userinfo).
     */
    protected function getUserByToken($token): array
    {
        $userinfoUrl = rtrim($this->baseUrl(), '/').'/oauth/userinfo';
        $response = $this->getHttpClient()->get($userinfoUrl, [
            'headers' => [
                'Authorization' => 'Bearer '.$token,
                'Accept'        => 'application/json',
            ],
        ]);

        return json_decode((string) $response->getBody(), true) ?? [];
    }

    /**
     * Map provider response to Socialite User.
     */
    protected function mapUserToObject(array $user): \Laravel\Socialite\Two\User
    {
        return (new User())->setRaw($user)->map([
            'name'      => $user['first_name'] . ' ' . $user['last_name'],
            'nickname'  => $user['nickname'] ?? null,
            'id'        => $user['sub'] ?? $user['id'] ?? null,
            'first_name'=> $user['first_name'] ?? null,
            'last_name' => $user['last_name'] ?? null,
            'email'     => $user['email'] ?? null,
            'profile_picture'   => $user['pofile_picture'] ?? null,
            'profile'   => $user['profile'] ?? null,
            'avatar'    => $user['profile_picture'] ?? null,
        ]);
    }

    /**
     * Add any extra params your SSO needs (audience, prompt, acr_values, etc.)
     */
    protected function getCodeFields($state = null): array
    {
        $fields = parent::getCodeFields($state);

        return $fields;
    }

    public function getAccessTokenResponse($code)
    {
        $fields = $this->getTokenFields($code);
        return parent::getAccessTokenResponse($code);
    }


    protected function baseUrl(): string
    {
        return 'http://event-gateway.test';
    }
}
