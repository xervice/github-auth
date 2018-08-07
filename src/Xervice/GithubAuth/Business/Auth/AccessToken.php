<?php


namespace Xervice\GithubAuth\Business\Auth;


use DataProvider\GithubAccessTokenRequestDataProvider;
use DataProvider\GithubAccessTokenResponseDataProvider;
use GuzzleHttp\Client;
use Xervice\GithubAuth\Business\Query\QueryBuilderInterface;

class AccessToken implements AccessTokenInterface
{
    /**
     * @var string
     */
    private $clientId;

    /**
     * @var string
     */
    private $clientSecret;

    /**
     * @var \Xervice\GithubAuth\Business\Query\QueryBuilderInterface
     */
    private $queryBuilder;

    /**
     * AccessToken constructor.
     *
     * @param string $clientId
     * @param string $clientSecret
     * @param \Xervice\GithubAuth\Business\Query\QueryBuilderInterface $queryBuilder
     */
    public function __construct(string $clientId, string $clientSecret, QueryBuilderInterface $queryBuilder)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->queryBuilder = $queryBuilder;
    }

    /**
     * @param \DataProvider\GithubAccessTokenRequestDataProvider $requestDataProvider
     *
     * @return \DataProvider\GithubAccessTokenResponseDataProvider
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAccessToken(
        GithubAccessTokenRequestDataProvider $requestDataProvider
    ): void {
        $client = new Client();

        $client->request(
            'POST',
            $this->getAccessTokenUrl($requestDataProvider),
            [
                'headers'       => [
                    'Accept' => 'application/json'
                ]
            ]
        );
    }

    /**
     * @param \DataProvider\GithubAccessTokenRequestDataProvider $requestDataProvider
     *
     * @return string
     */
    private function getAccessTokenUrl(GithubAccessTokenRequestDataProvider $requestDataProvider): string
    {
        $this->queryBuilder->appendParams(
            [
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
                'code'          => $requestDataProvider->getCode(),
                'redirect_uri'  => $requestDataProvider->getRedirectUrl(),
                'state'         => $requestDataProvider->getState()
            ]
        );
        return $this->queryBuilder->getUrl();
    }
}