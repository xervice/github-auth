<?php
declare(strict_types=1);

namespace Xervice\GithubAuth\Communication;


use Xervice\Core\Business\Model\Dependency\DependencyContainerInterface;
use Xervice\Core\Business\Model\Dependency\Provider\AbstractDependencyProvider;

class GithubAuthDependencyProvider extends AbstractDependencyProvider
{
    public const USER_FACADE = 'user.facade';
    public const LOG_FACADE = 'log.facade';

    /**
     * @param \Xervice\Core\Business\Model\Dependency\DependencyContainerInterface $container
     *
     * @return \Xervice\Core\Business\Model\Dependency\DependencyContainerInterface
     */
    public function handleDependencies(DependencyContainerInterface $container): DependencyContainerInterface
    {
        $container[self::USER_FACADE] = function (DependencyContainerInterface $container) {
            return $container->getLocator()->user()->facade();
        };

        $container[self::LOG_FACADE] = function (DependencyContainerInterface $container) {
            return $container->getLocator()->logger()->facade();
        };

        return $container;
    }
}