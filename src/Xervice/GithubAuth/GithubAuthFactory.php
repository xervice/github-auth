<?php


namespace Xervice\GithubAuth;


use Xervice\Core\Factory\AbstractFactory;
use Xervice\GithubAuth\Business\Api\GithubClient;
use Xervice\GithubAuth\Business\Api\GithubClientInterface;
use Xervice\GithubAuth\Business\Auth\AccessToken;
use Xervice\GithubAuth\Business\Auth\AccessTokenInterface;
use Xervice\GithubAuth\Business\Auth\Redirector;
use Xervice\GithubAuth\Business\Auth\RedirectorInterface;
use Xervice\GithubAuth\Business\Query\QueryBuilder;
use Xervice\GithubAuth\Business\Query\QueryBuilderInterface;

/**
 * @method \Xervice\GithubAuth\GithubAuthConfig getConfig()
 */
class GithubAuthFactory extends AbstractFactory
{
    /**
     * @return \Xervice\GithubAuth\Business\Api\GithubClientInterface
     */
    public function createGithubClient(): GithubClientInterface
    {
        return new GithubClient();
    }

    /**
     * @return \Xervice\GithubAuth\Business\Auth\AccessTokenInterface
     */
    public function createAccessToken(): AccessTokenInterface
    {
        return new AccessToken(
            $this->getConfig()->getClientId(),
            $this->getConfig()->getClientSecret(),
            $this->getConfig()->getAccessTokenUrl()
        );
    }

    /**
     * @return \Xervice\GithubAuth\Business\Auth\RedirectorInterface
     */
    public function createRedirector(): RedirectorInterface
    {
        return new Redirector(
            $this->getConfig()->getClientId(),
            $this->createQueryBuilder(
                $this->getConfig()->getAuthUrl()
            )
        );
    }

    /**
     * @param string|null $url
     *
     * @return \Xervice\GithubAuth\Business\Query\QueryBuilderInterface
     */
    public function createQueryBuilder(string $url = null): QueryBuilderInterface
    {
        return new QueryBuilder($url);
    }
}