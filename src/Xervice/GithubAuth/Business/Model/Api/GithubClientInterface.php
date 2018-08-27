<?php

namespace Xervice\GithubAuth\Business\Model\Api;

use DataProvider\GithubRequestDataProvider;

interface GithubClientInterface
{
    /**
     * @param \DataProvider\GithubRequestDataProvider $requestDataProvider
     * @param array $params
     *
     * @return array
     */
    public function getFromGithub(GithubRequestDataProvider $requestDataProvider, ...$params): array;
}