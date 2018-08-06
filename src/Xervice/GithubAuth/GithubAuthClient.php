<?php


namespace Xervice\GithubAuth;


use DataProvider\GithubRequestDataProvider;
use Xervice\Core\Client\AbstractClient;

/**
 * @method \Xervice\GithubAuth\GithubAuthFactory getFactory()
 * @method \Xervice\GithubAuth\GithubAuthConfig getConfig()
 */
class GithubAuthClient extends AbstractClient
{
    /**
     * @param \DataProvider\GithubRequestDataProvider $requestDataProvider
     * @param mixed ...$params
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getFromGithub(GithubRequestDataProvider $requestDataProvider, ...$params)
    {
        return $this->getFactory()->createGithubClient()->getFromGithub($requestDataProvider, ...$params);
    }
}