<?php

namespace Xervice\GithubAuth\Business\Api;

use DataProvider\GithubRequestDataProvider;

interface GithubClientInterface
{
    /**
     * @param \DataProvider\GithubRequestDataProvider $requestDataProvider
     * @param array $params
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getFromGithub(GithubRequestDataProvider $requestDataProvider, ...$params): array;
}