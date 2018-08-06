<?php


namespace Xervice\GithubAuth;


use Xervice\Core\Config\AbstractConfig;

class GithubAuthConfig extends AbstractConfig
{
    public const CLIENT_ID = 'github.client.id';
    public const CLIENT_SECRET = 'github.client.secret';
    public const AUTH_URL = 'github.auth.url';
    public const ACCESS_TOKEN_URL = 'github.access.token.url';

    /**
     * @return string
     */
    public function getAuthUrl(): string
    {
        return $this->get(self::AUTH_URL, 'https://github.com/login/oauth/authorize');
    }

    /**
     * @return string
     */
    public function getAccessTokenUrl(): string
    {
        return $this->get(self::ACCESS_TOKEN_URL, 'https://github.com/login/oauth/access_token');
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->get(self::CLIENT_ID);
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->get(self::CLIENT_SECRET);
    }
}