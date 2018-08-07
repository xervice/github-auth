<?php


namespace Xervice\GithubAuth\Business\Auth;


use DataProvider\GithubAccessTokenRequestDataProvider;
use DataProvider\GithubAccessTokenResponseDataProvider;
use GuzzleHttp\Client;

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
     * @var string
     */
    private $accessTokenUrl;

    /**
     * AccessToken constructor.
     *
     * @param string $clientId
     * @param string $clientSecret
     * @param string $accessTokenUrl
     */
    public function __construct(string $clientId, string $clientSecret, string $accessTokenUrl)
    {
        $this->clientId = $clientId;
        $this->clientSecret = $clientSecret;
        $this->accessTokenUrl = $accessTokenUrl;
    }

    /**
     * @param \DataProvider\GithubAccessTokenRequestDataProvider $requestDataProvider
     *
     * @return \DataProvider\GithubAccessTokenResponseDataProvider
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getAccessToken(
        GithubAccessTokenRequestDataProvider $requestDataProvider
    ): GithubAccessTokenResponseDataProvider {
        $client = new Client();
        $response = $client->request(
            'POST',
            $this->accessTokenUrl,
            [
                'headers'       => [
                    'Accept' => 'application/json'
                ],
                'client_id'     => $this->clientId,
                'client_secret' => $this->clientSecret,
                'code'          => $requestDataProvider->getCode(),
                'redirect_uri'  => $requestDataProvider->getRedirectUrl(),
                'state'         => $requestDataProvider->getState(),
            ]
        );

        $responseData = json_decode(
            $response->getBody(),
            true
        );

        $tokenResponse = new GithubAccessTokenResponseDataProvider();
        $tokenResponse
            ->setAccessToken($responseData['access_token'])
            ->setTokenType($responseData['token_type']);

        return $tokenResponse;
    }
}