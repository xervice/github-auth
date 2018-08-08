<?php

namespace Xervice\GithubAuth\Business\Auth;

use DataProvider\GithubAccessTokenRequestDataProvider;
use DataProvider\GithubAccessTokenResponseDataProvider;

interface AccessTokenInterface
{
    /**
     * @param \DataProvider\GithubAccessTokenRequestDataProvider $requestDataProvider
     *
     * @return \DataProvider\GithubAccessTokenResponseDataProvider
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAccessToken(
        GithubAccessTokenRequestDataProvider $requestDataProvider
    ): GithubAccessTokenResponseDataProvider;
}