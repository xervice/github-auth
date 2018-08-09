<?php
namespace XerviceTest\GithubAuth;

use Xervice\Core\Locator\Dynamic\DynamicLocator;

/**
 * @method \Xervice\GithubAuth\GithubAuthFacade getFacade()
 */
class FacadeTest extends \Codeception\Test\Unit
{
    use DynamicLocator;

    /**
     * @group Xervice
     * @group GithubAuth
     * @group Facade
     * @group Integration
     *
     * @throws \Core\Locator\Dynamic\ServiceNotParseable
     */
    public function testGet()
    {
        $this->assertEquals(
            'https://github.com/login/oauth/authorize?client_id=client.id&scope=myscope&redirect_uri=https://my.test/github/auth?&allow_signup=1',
            urldecode($this->getFacade()->getGithubAuthUrl('myscope')->getUrl())
        );
    }
}