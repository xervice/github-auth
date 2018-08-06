<?php


namespace Xervice\GithubAuth\Business\Auth;


use DataProvider\GithubAuthRequestDataProvider;
use Xervice\GithubAuth\Business\Query\QueryBuilderInterface;

class Redirector implements RedirectorInterface
{
    /**
     * @var string
     */
    private $clientId;

    /**
     * @var \Xervice\GithubAuth\Business\Query\QueryBuilderInterface
     */
    private $queryBuilder;

    /**
     * Redirector constructor.
     *
     * @param string $clientId
     * @param \Xervice\GithubAuth\Business\Query\QueryBuilderInterface $queryBuilder
     */
    public function __construct(string $clientId, QueryBuilderInterface $queryBuilder)
    {
        $this->clientId = $clientId;
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @param \DataProvider\GithubAuthRequestDataProvider $authDataProvider
     */
    public function redirectToAuth(GithubAuthRequestDataProvider $authDataProvider): void
    {
        $this->queryBuilder->appendParams(
            [
                'client_id'    => $this->clientId,
                'scope'        => $authDataProvider->getScope(),
                'redirect_uri' => $authDataProvider->getRedirectUrl(),
                'allow_signup' => $authDataProvider->getAllowSignup(),
                'state'        => $authDataProvider->getState()
            ]
        );

        header('Location: ' . $this->queryBuilder->getUrl());
        exit;
    }
}