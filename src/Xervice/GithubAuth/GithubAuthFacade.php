<?php


namespace Xervice\GithubAuth;


use DataProvider\GithubAccessTokenRequestDataProvider;
use DataProvider\GithubAccessTokenResponseDataProvider;
use DataProvider\GithubAuthRequestDataProvider;
use Xervice\Core\Facade\AbstractFacade;
use Xervice\GithubAuth\Business\Query\QueryBuilderInterface;

/**
 * @method \Xervice\GithubAuth\GithubAuthFactory getFactory()
 * @method \Xervice\GithubAuth\GithubAuthConfig getConfig()
 * @method \Xervice\GithubAuth\GithubAuthClient getClient()
 */
class GithubAuthFacade extends AbstractFacade
{
    /**
     * @param string $scope
     * @param string $state
     *
     * @return \Xervice\GithubAuth\Business\Query\QueryBuilderInterface
     */
    public function getGithubAuthUrl(string $scope, string $state = null): QueryBuilderInterface
    {
        return $this->getFactory()->createGithubAuthUrl($scope, $state);
    }

    /**
     * @param \DataProvider\GithubAccessTokenRequestDataProvider $requestDataProvider
     *
     * @return \DataProvider\GithubAccessTokenResponseDataProvider
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAccessToken(
        GithubAccessTokenRequestDataProvider $requestDataProvider
    ): GithubAccessTokenResponseDataProvider
    {
        return $this->getFactory()->createAccessToken()->getAccessToken($requestDataProvider);
    }
}