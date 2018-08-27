<?php
declare(strict_types=1);

namespace Xervice\GithubAuth\Communication;


use Xervice\Core\Business\Model\Factory\AbstractCommunicationFactory;
use Xervice\GithubAuth\GithubAuthDependencyProvider;
use Xervice\Logger\Business\LoggerFacade;
use Xervice\User\Business\UserFacade;

class GithubAuthCommunicationFactory extends AbstractCommunicationFactory
{
    /**
     * @return \Xervice\Logger\Business\LoggerFacade
     */
    public function getLoggerFacade(): LoggerFacade
    {
        return $this->getDependency(GithubAuthDependencyProvider::LOG_FACADE);
    }

    /**
     * @return \Xervice\User\Business\UserFacade
     */
    public function getUserFacade(): UserFacade
    {
        return $this->getDependency(GithubAuthDependencyProvider::USER_FACADE);
    }
}