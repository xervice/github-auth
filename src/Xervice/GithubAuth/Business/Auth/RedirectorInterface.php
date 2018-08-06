<?php

namespace Xervice\GithubAuth\Business\Auth;

use DataProvider\GithubAuthRequestDataProvider;

interface RedirectorInterface
{
    /**
     * @param \DataProvider\GithubAuthRequestDataProvider $authDataProvider
     */
    public function redirectToAuth(GithubAuthRequestDataProvider $authDataProvider): void;
}