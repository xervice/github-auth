<?php


namespace Xervice\GithubAuth\Business\Model\Auth;


use DataProvider\GithubAccessTokenRequestDataProvider;
use DataProvider\GithubAccessTokenResponseDataProvider;
use GuzzleHttp\Client;
use Xervice\GithubAuth\Business\Exception\GithubException;
use Xervice\GithubAuth\Business\Model\Query\QueryBuilderInterface;

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
     * @var \Xervice\GithubAuth\Business\Model\Query\QueryBuilderInterface
     */
    private $queryBuilder;

    /**
     * AccessToken constructor.
     *
     * @param string $clientId
     * @param string $clientSecret
     * @param \Xervice\GithubAuth\Business\Model\Query\QueryBuilderInterface $queryBuilder
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
     */
    public function getAccessToken(
        GithubAccessTokenRequestDataProvider $requestDataProvider
    ): GithubAccessTokenResponseDataProvider {
        $client = new Client();
        $response = $client->request(
            'POST',
            $this->getAccessTokenUrl($requestDataProvider),
            [
                'headers'       => [
                    'Accept' => 'application/json'
                ]
            ]
        );

        $responseData = json_decode(
            $response->getBody(),
            true
        );

        if (!isset($responseData['access_token'])) {
            throw new GithubException($responseData['error'] ?? 'No access token from github');
        }

        $tokenResponse = new GithubAccessTokenResponseDataProvider();
        $tokenResponse
            ->setAccessToken($responseData['access_token'])
            ->setTokenType($responseData['token_type']);

        return $tokenResponse;
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