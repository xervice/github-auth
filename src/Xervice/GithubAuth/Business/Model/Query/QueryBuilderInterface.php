<?php

namespace Xervice\GithubAuth\Business\Model\Query;

interface QueryBuilderInterface
{
    /**
     * @param array $params
     *
     * @return \Xervice\GithubAuth\Business\Model\Query\QueryBuilderInterface
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