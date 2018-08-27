<?php


namespace Xervice\GithubAuth\Business;


use DataProvider\GithubAccessTokenRequestDataProvider;
use DataProvider\GithubAccessTokenResponseDataProvider;
use DataProvider\GithubRequestDataProvider;
use DataProvider\UserAuthDataProvider;
use Xervice\Core\Business\Model\Facade\AbstractFacade;
use Xervice\GithubAuth\Business\Model\Query\QueryBuilderInterface;

/**
 * @method \Xervice\GithubAuth\Business\GithubAuthBusinessFactory getFactory()
 * @method \Xervice\GithubAuth\GithubAuthConfig getConfig()
 */
class GithubAuthFacade extends AbstractFacade
{
    /**
     * @param string $scope
     * @param string $state
     *
     * @return \Xervice\GithubAuth\Business\Model\Query\QueryBuilderInterface
     */
    public function getGithubAuthUrl(string $scope, string $state = null): QueryBuilderInterface
    {
        return $this->getFactory()->createGithubAuthUrl($scope, $state);
    }

    /**
     * @param \DataProvider\GithubAccessTokenRequestDataProvider $requestDataProvider
     *
     * @return \DataProvider\GithubAccessTokenResponseDataProvider
     */
    public function getAccessToken(
        GithubAccessTokenRequestDataProvider $requestDataProvider
    ): GithubAccessTokenResponseDataProvider
    {
        return $this->getFactory()->createAccessToken()->getAccessToken($requestDataProvider);
    }

    /**
     * @param \DataProvider\GithubRequestDataProvider $requestDataProvider
     * @param mixed ...$params
     *
     * @return array
     */
    public function getFromGithub(GithubRequestDataProvider $requestDataProvider, ...$params)
    {
        return $this->getFactory()->createGithubClient()->getFromGithub($requestDataProvider, ...$params);
    }

    /**
     * @param string $code
     *
     * @return \DataProvider\UserAuthDataProvider
     */
    public function createUserFromGithub(string $code): UserAuthDataProvider
    {
        return $this->getFactory()->createGithubAuth()->createUserFromGithub($code);
    }
}