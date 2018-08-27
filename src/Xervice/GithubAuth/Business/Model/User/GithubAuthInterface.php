<?php

namespace Xervice\GithubAuth\Business\Model\User;


use DataProvider\UserAuthDataProvider;

interface GithubAuthInterface
{
    /**
     * @param string $code
     *
     * @return \DataProvider\UserAuthDataProvider
     */
    public function createUserFromGithub(string $code): UserAuthDataProvider;
}