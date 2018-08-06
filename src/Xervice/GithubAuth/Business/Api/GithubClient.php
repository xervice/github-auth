<?php


namespace Xervice\GithubAuth\Business\Api;


use DataProvider\GithubRequestDataProvider;
use GuzzleHttp\Client;

class GithubClient implements GithubClientInterface
{
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
            $requestDataProvider->getApiUrl(),
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