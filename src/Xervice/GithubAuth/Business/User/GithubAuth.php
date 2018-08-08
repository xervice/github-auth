<?php


namespace Xervice\GithubAuth\Business\User;


use DataProvider\GithubAccessTokenRequestDataProvider;
use DataProvider\GithubAccessTokenResponseDataProvider;
use DataProvider\GithubRequestDataProvider;
use DataProvider\UserAuthDataProvider;
use DataProvider\UserCredentialDataProvider;
use DataProvider\UserDataProvider;
use DataProvider\UserLoginDataProvider;
use Xervice\GithubAuth\Business\Api\GithubClientInterface;
use Xervice\GithubAuth\Business\Auth\AccessTokenInterface;
use Xervice\GithubAuth\GithubAuthConfig;
use Xervice\User\UserFacade;

class GithubAuth implements GithubAuthInterface
{
    /**
     * @var \Xervice\User\UserFacade
     */
    private $userFacade;

    /**
     * @var \Xervice\GithubAuth\Business\Auth\AccessTokenInterface
     */
    private $accessToken;

    /**
     * @var \Xervice\GithubAuth\Business\Api\GithubClientInterface
     */
    private $githubClient;

    /**
     * GithubAuth constructor.
     *
     * @param \Xervice\User\UserFacade $userFacade
     * @param \Xervice\GithubAuth\Business\Auth\AccessTokenInterface $accessToken
     */
    public function __construct(
        UserFacade $userFacade,
        AccessTokenInterface $accessToken,
        GithubClientInterface $githubClient
    ) {
        $this->userFacade = $userFacade;
        $this->accessToken = $accessToken;
        $this->githubClient = $githubClient;
    }

    /**
     * @param string $code
     *
     * @return \DataProvider\UserAuthDataProvider
     * @throws \GuzzleHttp\Exception\GuzzleException
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Xervice\User\Business\Exception\UserException
     */
    public function createUserFromGithub(string $code): UserAuthDataProvider
    {
        $token = $this->getTokenFromGithub($code);
        $emails = $this->getEmailsFromGithub($token);

        $newUser = new UserDataProvider();
        foreach ($emails as $email) {
            if ($email['primary'] === true) {
                $newUser->setEmail($email['email']);
            }
        }

        $user = $this->userFacade->getUserFromEmail($newUser->getEmail());
        if (!$user->hasUserId()) {
            $user = $this->createNewGithubUser($token, $newUser);
        } elseif (!$this->userHaveLoginType($user)) {
            $login = $this->getGithubLoginType($token);
            $user->addUserLogin($login);
            $this->userFacade->updateUser($user);
        }

        $auth = new UserAuthDataProvider();

        return $auth
            ->setType('Github')
            ->setUser($user)
            ->setCredential(
                (new UserCredentialDataProvider())->setHash($token->getAccessToken())
            );
    }

    /**
     * @param \DataProvider\UserDataProvider $userDataProvider
     *
     * @return bool
     */
    private function userHaveLoginType(UserDataProvider $userDataProvider): bool
    {
        foreach ($userDataProvider->getUserLogins() as $login) {
            if ($login->getType() === 'Github') {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $code
     *
     * @return \DataProvider\GithubAccessTokenRequestDataProvider|\DataProvider\GithubAccessTokenResponseDataProvider
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getTokenFromGithub(string $code): GithubAccessTokenResponseDataProvider
    {
        $token = new GithubAccessTokenRequestDataProvider();
        $token
            ->setCode($code)
            ->setRedirectUrl(
                $this->getFactory()->createRedirectQueryBuilder(GithubAuthConfig::ERROR_PATH)
            );

        return $this->accessToken->getAccessToken($token);
}

    /**
     * @param \DataProvider\GithubAccessTokenResponseDataProvider $token
     *
     * @return array
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    private function getEmailsFromGithub(GithubAccessTokenResponseDataProvider $token): array
    {
        $userRequest = new GithubRequestDataProvider();
        $userRequest
            ->setAccessToken($token->getAccessToken())
            ->setApiUrl('/user/emails');

        return $this->githubClient->getFromGithub($userRequest);
    }

    /**
     * @param $token
     * @param $newUser
     *
     * @return \DataProvider\UserDataProvider
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Xervice\User\Business\Exception\UserException
     */
    private function createNewGithubUser($token, $newUser): \DataProvider\UserDataProvider
    {
        $login = $this->getGithubLoginType($token);
        $newUser->addUserLogin($login);

        $user = $this->userFacade->createUser($newUser);
        return $user;
}

    /**
     * @param $token
     *
     * @return \DataProvider\UserLoginDataProvider
     */
    private function getGithubLoginType($token): \DataProvider\UserLoginDataProvider
    {
        $credentials = new UserCredentialDataProvider();
        $credentials->setHash($token->getAccessToken());

        $login = new UserLoginDataProvider();
        $login
            ->setType('Github')
            ->setUserCredential($credentials);

        return $login;
}
}