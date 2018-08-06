<?php


namespace Xervice\GithubAuth;


use DataProvider\GithubAccessTokenRequestDataProvider;
use DataProvider\GithubAccessTokenResponseDataProvider;
use DataProvider\GithubAuthRequestDataProvider;
use Xervice\Core\Facade\AbstractFacade;

/**
 * @method \Xervice\GithubAuth\GithubAuthFactory getFactory()
 * @method \Xervice\GithubAuth\GithubAuthConfig getConfig()
 * @method \Xervice\GithubAuth\GithubAuthClient getClient()
 */
class GithubAuthFacade extends AbstractFacade
{
    /**
     * @param \DataProvider\GithubAuthRequestDataProvider $authDataProvider
     *
     * @return string
     */
    public function authForGithub(GithubAuthRequestDataProvider $authDataProvider): string
    {
        $this->getFactory()->createRedirector()->redirectToAuth($authDataProvider);
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