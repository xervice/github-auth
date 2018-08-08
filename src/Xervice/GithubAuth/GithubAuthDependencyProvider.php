<?php


namespace Xervice\GithubAuth;


use Xervice\Core\Dependency\DependencyProviderInterface;
use Xervice\Core\Dependency\Provider\AbstractProvider;

class GithubAuthDependencyProvider extends AbstractProvider
{
    public const USER_FACADE = 'user.facade';
    public const LOG_FACADE = 'log.facade';

    /**
     * @param \Xervice\Core\Dependency\DependencyProviderInterface $dependencyProvider
     */
    public function handleDependencies(DependencyProviderInterface $dependencyProvider): void
    {
        $dependencyProvider[self::USER_FACADE] = function (DependencyProviderInterface $dependencyProvider) {
            return $dependencyProvider->getLocator()->user()->facade();
        };

        $dependencyProvider[self::LOG_FACADE] = function (DependencyProviderInterface $dependencyProvider) {
            return $dependencyProvider->getLocator()->logger()->facade();
        };
    }
}