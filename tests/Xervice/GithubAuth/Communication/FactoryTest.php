<?php
namespace XerviceTest\GithubAuth\Communication;
use Xervice\Core\Business\Model\Locator\Dynamic\Communication\DynamicCommunicationLocator;
use Xervice\Logger\Business\LoggerFacade;
use Xervice\User\Business\UserFacade;


/**
 * @method \Xervice\GithubAuth\Communication\GithubAuthCommunicationFactory getFactory()
 */
class FactoryTest extends \Codeception\Test\Unit
{
    use DynamicCommunicationLocator;

    /**
     * @group Xervice
     * @group GithubAuth
     * @group Communication
     * @group Factory
     * @group Integration
     */
    public function testGetUserFacade()
    {
        $this->assertInstanceOf(
            UserFacade::class,
            $this->getFactory()->getUserFacade()
        );
    }

    /**
     * @group Xervice
     * @group GithubAuth
     * @group Communication
     * @group Factory
     * @group Integration
     */
    public function testGetLoggerFacade()
    {
        $this->assertInstanceOf(
            LoggerFacade::class,
            $this->getFactory()->getLoggerFacade()
        );
    }
}