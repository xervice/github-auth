<?php
namespace XerviceTest\GithubOAuth\Business\Query;

use Xervice\GithubAuth\Business\Query\QueryBuilder;

class QueryBuilderTest extends \Codeception\Test\Unit
{
    /**
     * @group Xervice
     * @group GithubOauth
     * @group Business
     * @group Query
     * @group Unit
     */
    public function testAppendParam()
    {
        $queryBuilder = new QueryBuilder(
            'http://api.mytest.com/myTest?param1=test.test',
            [
                'param2' => 'test2.test2'
            ]
        );

        $this->assertEquals(
            'http://api.mytest.com/myTest?param1=test.test&param2=test2.test2',
            $queryBuilder->getUrl()
        );
    }

    /**
     * @group Xervice
     * @group GithubOauth
     * @group Business
     * @group Query
     * @group Unit
     */
    public function testAppendParamWithChange()
    {
        $queryBuilder = new QueryBuilder(
            'http://api.mytest.com/myTest?param1=test.test',
            [
                'param1' => 'testC.testC',
                'param2' => 'test2.test2'
            ]
        );

        $this->assertEquals(
            'http://api.mytest.com/myTest?param1=testC.testC&param2=test2.test2',
            $queryBuilder->getUrl()
        );
    }
}