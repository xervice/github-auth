<?php

use Xervice\DataProvider\DataProviderConfig;
use Xervice\GithubAuth\GithubAuthConfig;

$config[DataProviderConfig::DATA_PROVIDER_GENERATED_PATH] = dirname(__DIR__) . '/src/Generated';
$config[DataProviderConfig::DATA_PROVIDER_PATHS] = [
    dirname(__DIR__) . '/src/',
    dirname(__DIR__) . '/vendor/',
];


if (class_exists(GithubAuthConfig::class)) {
    $config[GithubAuthConfig::CLIENT_ID] = 'client.id';
    $config[GithubAuthConfig::REDIRECT_BASE_URL] = 'https://my.test';
}