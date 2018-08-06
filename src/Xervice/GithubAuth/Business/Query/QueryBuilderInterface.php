<?php

namespace Xervice\GithubAuth\Business\Query;

interface QueryBuilderInterface
{
    /**
     * @param array $params
     */
    public function appendParams(array $params): void;

    /**
     * @param string $url
     */
    public function setUrl(string $url): void;

    /**
     * @return string
     */
    public function getUrl(): string;
}