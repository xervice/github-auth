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
use Xervice\GithubAuth\Business\User\GithubAuth;
use Xervice\GithubAuth\Business\User\GithubAuthInterface;
use Xervice\GithubAuth\Communication\Controller\GithubController;
use Xervice\Logger\LoggerFacade;
use Xervice\User\UserFacade;

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
        return new GithubClient(
            $this->getConfig()->getApiBaseUrl()
        );
    }

    /**
     * @return \Xervice\GithubAuth\Business\User\GithubAuthInterface
     */
    public function createGithubAuth(): GithubAuthInterface
    {
        return new GithubAuth(
            $this->getUserFacade(),
            $this->createAccessToken(),
            $this->createGithubClient()
        );
    }

    /**
     * @return \Xervice\GithubAuth\Business\Auth\AccessTokenInterface
     */
    public function createAccessToken(): AccessTokenInterface
    {
        return new AccessToken(
            $this->getConfig()->getClientId(),
            $this->getConfig()->getClientSecret(),
            $this->createQueryBuilder(
                $this->getConfig()->getAccessTokenUrl()
            )
        );
    }

    /**
     * @param string $scope
     * @param string|null $state
     *
     * @return \Xervice\GithubAuth\Business\Query\QueryBuilderInterface
     */
    public function createGithubAuthUrl(string $scope, string $state = null)
    {
        return $this->createQueryBuilder(
            $this->getConfig()->getAuthUrl(),
            [
                'client_id'    => $this->getConfig()->getClientId(),
                'scope'        => $scope,
                'redirect_uri' => $this->createRedirectQueryBuilder(GithubAuthConfig::AUTH_PATH),
                'allow_signup' => $this->getConfig()->getAllowSignup(),
                'state'        => $state
            ]
        );
    }

    /**
     * @param string $path
     * @param array $params
     *
     * @return \Xervice\GithubAuth\Business\Query\QueryBuilderInterface
     */
    public function createRedirectQueryBuilder(string $path, array $params = []): QueryBuilderInterface
    {
        return $this->createQueryBuilder(
            $this->getConfig()->getRedirectBaseUrl() . $path,
            $params
        );
    }

    /**
     * @param string|null $url
     *
     * @param array $params
     *
     * @return \Xervice\GithubAuth\Business\Query\QueryBuilderInterface
     */
    public function createQueryBuilder(string $url = null, array $params = []): QueryBuilderInterface
    {
        return new QueryBuilder($url, $params);
    }

    /**
     * @return \Xervice\User\UserFacade
     */
    public function getUserFacade(): UserFacade
    {
        return $this->getDependency(GithubAuthDependencyProvider::USER_FACADE);
    }

    /**
     * @return \Xervice\Logger\LoggerFacade
     */
    public function getLoggerFacade(): LoggerFacade
    {
        return $this->getDependency(GithubAuthDependencyProvider::LOG_FACADE);
    }
}