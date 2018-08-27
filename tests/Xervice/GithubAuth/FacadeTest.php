<?php
namespace XerviceTest\GithubAuth;

use Xervice\Core\Business\Model\Locator\Dynamic\Business\DynamicBusinessLocator;

/**
 * @method \Xervice\GithubAuth\Business\GithubAuthFacade getFacade()
 */
class FacadeTest extends \Codeception\Test\Unit
{
    use DynamicBusinessLocator;

    /**
     * @group Xervice
     * @group GithubAuth
     * @group Facade
     * @group Integration
     */
    public function testGet()
    {
        $this->assertEquals(
            'https://github.com/login/oauth/authorize?client_id=client.id&scope=myscope&redirect_uri=https://my.test/github/auth?&allow_signup=1',
            urldecode($this->getFacade()->getGithubAuthUrl('myscope')->getUrl())
        );
    }
}