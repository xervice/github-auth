<?php


namespace Xervice\GithubAuth\Business;


use Xervice\Core\Business\Model\Factory\AbstractBusinessFactory;
use Xervice\GithubAuth\Business\Model\Api\GithubClient;
use Xervice\GithubAuth\Business\Model\Api\GithubClientInterface;
use Xervice\GithubAuth\Business\Model\Auth\AccessToken;
use Xervice\GithubAuth\Business\Model\Auth\AccessTokenInterface;
use Xervice\GithubAuth\Business\Model\Query\QueryBuilder;
use Xervice\GithubAuth\Business\Model\Query\QueryBuilderInterface;
use Xervice\GithubAuth\Business\Model\User\GithubAuth;
use Xervice\GithubAuth\Business\Model\User\GithubAuthInterface;
use Xervice\GithubAuth\GithubAuthConfig;
use Xervice\GithubAuth\GithubAuthDependencyProvider;
use Xervice\Logger\Business\LoggerFacade;
use Xervice\User\Business\UserFacade;

/**
 * @method \Xervice\GithubAuth\GithubAuthConfig getConfig()
 */
class GithubAuthBusinessFactory extends AbstractBusinessFactory
{
    /**
     * @return \Xervice\GithubAuth\Business\Model\Api\GithubClientInterface
     */
    public function createGithubClient(): GithubClientInterface
    {
        return new GithubClient(
            $this->getConfig()->getApiBaseUrl()
        );
    }

    /**
     * @return \Xervice\GithubAuth\Business\Model\User\GithubAuthInterface
     */
    public function createGithubAuth(): GithubAuthInterface
    {
        return new GithubAuth(
            $this->getUserFacade(),
            $this->createAccessToken(),
            $this->createGithubClient(),
            $this->createRedirectQueryBuilder(GithubAuthConfig::ERROR_PATH)
        );
    }

    /**
     * @return \Xervice\GithubAuth\Business\Model\Auth\AccessTokenInterface
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
     * @return \Xervice\GithubAuth\Business\Model\Query\QueryBuilderInterface
     */
    public function createGithubAuthUrl(string $scope, string $state = null): QueryBuilderInterface
    {
        return $this->createQueryBuilder(
            $this->getConfig()->getAuthUrl(),
            [
                'client_id'    => $this->getConfig()->getClientId(),
                'scope'        => $scope,
                'redirect_uri' => $this->createRedirectQueryBuilder(GithubAuthConfig::AUTH_PATH)->getUrl(),
                'allow_signup' => $this->getConfig()->getAllowSignup(),
                'state'        => $state
            ]
        );
    }

    /**
     * @param string $path
     * @param array $params
     *
     * @return \Xervice\GithubAuth\Business\Model\Query\QueryBuilderInterface
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
     * @param array $params
     *
     * @return \Xervice\GithubAuth\Business\Model\Query\QueryBuilderInterface
     */
    public function createQueryBuilder(string $url = null, array $params = []): QueryBuilderInterface
    {
        return new QueryBuilder($url, $params);
    }

    /**
     * @return \Xervice\User\Business\UserFacade
     */
    public function getUserFacade(): UserFacade
    {
        return $this->getDependency(GithubAuthDependencyProvider::USER_FACADE);
    }
}