<?php


namespace Xervice\GithubAuth\Business\Model\User;


use DataProvider\UserAuthDataProvider;
use DataProvider\UserCredentialDataProvider;
use Xervice\User\Business\Dependency\Authenticator\Login\LoginInterface;

class GithubLogin implements LoginInterface
{
    /**
     * @param \DataProvider\UserAuthDataProvider $authDataProvider
     * @param \DataProvider\UserCredentialDataProvider $userCredentialDataProvider
     *
     * @return bool
     */
    public function auth(
        UserAuthDataProvider $authDataProvider,
        UserCredentialDataProvider $userCredentialDataProvider
    ): bool {
        return $authDataProvider->getCredential()->getHash() === $userCredentialDataProvider->getHash();
    }
}