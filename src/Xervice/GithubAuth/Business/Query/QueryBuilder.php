<?php


namespace Xervice\GithubAuth\Business\Query;


class QueryBuilder implements QueryBuilderInterface
{
    /**
     * @var string
     */
    private $url;

    /**
     * QueryBuilder constructor.
     *
     * @param string $url
     */
    public function __construct(string $url = null)
    {
        $this->url = $url;
    }

    /**
     * @param array $params
     */
    public function appendParams(array $params): void
    {
        $urlData = parse_url($this->url);
        if (isset($urlData['query'])) {
            parse_str($urlData['query'], $queryData);
            $queryData = array_merge($queryData, $params);
        } else {
            $queryData = $params;
        }
        $urlData['query'] = http_build_query($queryData);

        if (function_exists('http_build_url')) {
            $this->url = http_build_url($urlData);
        } else {
            $this->url = sprintf(
                '%s://%s%s?%s',
                $urlData['scheme'],
                $urlData['host'],
                $urlData['path'],
                $urlData['query']
            );
        }
    }

    /**
     * @param string $url
     */
    public function setUrl(string $url): void
    {
        $this->url = $url;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }
}