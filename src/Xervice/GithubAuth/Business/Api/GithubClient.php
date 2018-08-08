<?php


namespace Xervice\GithubAuth\Business\Api;


use DataProvider\GithubRequestDataProvider;
use GuzzleHttp\Client;

class GithubClient implements GithubClientInterface
{
    /**
     * @var string
     */
    private $apiBaseUrl;

    /**
     * GithubClient constructor.
     *
     * @param string $apiBaseUrl
     */
    public function __construct(string $apiBaseUrl)
    {
        $this->apiBaseUrl = $apiBaseUrl;
    }

    /**
     * @param \DataProvider\GithubRequestDataProvider $requestDataProvider
     * @param array $params
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getFromGithub(GithubRequestDataProvider $requestDataProvider, ...$params): array
    {
        $client = new Client();
        $response = $client->request(
            'GET',
            $this->apiBaseUrl . $requestDataProvider->getApiUrl(),
            array_merge(
                [
                    'headers' => [
                        'Authorization' => 'token ' . $requestDataProvider->getAccessToken()
                    ]
                ],
                $params
            )
        );

        return json_decode(
            $response->getBody(),
            true
        );
    }
}