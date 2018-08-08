<?php

namespace Xervice\GithubAuth\Business\Query;

interface QueryBuilderInterface
{
    /**
     * @param array $params
     *
     * @return \Xervice\GithubAuth\Business\Query\QueryBuilderInterface
     */
    public function appendParams(array $params): QueryBuilderInterface;

    /**
     * @param string $url
     */
    public function setUrl(string $url): void;

    /**
     * @return string
     */
    public function getUrl(): string;
}