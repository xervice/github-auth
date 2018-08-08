<?php


namespace Xervice\GithubAuth;


use Xervice\Core\Config\AbstractConfig;

class GithubAuthConfig extends AbstractConfig
{
    public const CLIENT_ID = 'github.client.id';
    public const CLIENT_SECRET = 'github.client.secret';
    public const AUTH_URL = 'github.auth.url';
    public const ACCESS_TOKEN_URL = 'github.access.token.url';
    public const REDIRECT_BASE_URL = 'github.redirect.base.url';
    public const ALLOW_SIGNUP = 'github.allow.signup';
    public const API_BASE_URL = 'github.api.baseurl';

    public const LOGIN_PATH = '/github/login';
    public const ERROR_PATH = '/github/error';
    public const AUTH_PATH = '/github/auth';
    public const AFTER_LOGIN_PATH = '/';
    public const AFTER_ERROR_PATH = '/';

    /**
     * @return string
     */
    public function getApiBaseUrl(): string
    {
        return $this->get(self::API_BASE_URL, 'https://api.github.com');
    }

    /**
     * @return bool
     */
    public function getAllowSignup(): bool
    {
        return $this->get(self::ALLOW_SIGNUP, true);
    }

    /**
     * @return string
     */
    public function getRedirectBaseUrl(): string
    {
        return $this->get(self::REDIRECT_BASE_URL);
    }

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