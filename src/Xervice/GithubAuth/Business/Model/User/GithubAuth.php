<?php


namespace Xervice\GithubAuth\Business\Model\User;


use DataProvider\GithubAccessTokenRequestDataProvider;
use DataProvider\GithubAccessTokenResponseDataProvider;
use DataProvider\GithubRequestDataProvider;
use DataProvider\UserAuthDataProvider;
use DataProvider\UserCredentialDataProvider;
use DataProvider\UserDataProvider;
use DataProvider\UserLoginDataProvider;
use Xervice\GithubAuth\Business\Model\Api\GithubClientInterface;
use Xervice\GithubAuth\Business\Model\Auth\AccessTokenInterface;
use Xervice\GithubAuth\Business\Model\Query\QueryBuilderInterface;
use Xervice\User\Business\UserFacade;

class GithubAuth implements GithubAuthInterface
{
    /**
     * @var \Xervice\User\Business\UserFacade
     */
    private $userFacade;

    /**
     * @var \Xervice\GithubAuth\Business\Model\Auth\AccessTokenInterface
     */
    private $accessToken;

    /**
     * @var \Xervice\GithubAuth\Business\Model\Api\GithubClientInterface
     */
    private $githubClient;

    /**
     * @var \Xervice\GithubAuth\Business\Model\Query\QueryBuilderInterface
     */
    private $errorUrl;

    /**
     * GithubAuth constructor.
     *
     * @param \Xervice\User\Business\UserFacade $userFacade
     * @param \Xervice\GithubAuth\Business\Model\Auth\AccessTokenInterface $accessToken
     * @param \Xervice\GithubAuth\Business\Model\Api\GithubClientInterface $githubClient
     * @param \Xervice\GithubAuth\Business\Model\Query\QueryBuilderInterface $errorUrl
     */
    public function __construct(
        UserFacade $userFacade,
        AccessTokenInterface $accessToken,
        GithubClientInterface $githubClient,
        QueryBuilderInterface $errorUrl
    ) {
        $this->userFacade = $userFacade;
        $this->accessToken = $accessToken;
        $this->githubClient = $githubClient;
        $this->errorUrl = $errorUrl;
    }

    /**
     * @param string $code
     *
     * @return \DataProvider\UserAuthDataProvider
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
        }
        elseif (!$this->userHaveLoginType($user)) {
            $login = $this->getGithubLoginType($token);
            $user->addUserLogin($login);
            $this->userFacade->updateUser($user);
        }
        else {
            foreach ($user->getUserLogins() as $login) {
                if ($login->getType() === 'Github') {
                    $login->getUserCredential()->setHash($token->getAccessToken());
                    $this->userFacade->updateCredential($login->getUserCredential());
                }
            }
        }

        $auth = new UserAuthDataProvider();

        return $auth
            ->setType('Github')
            ->setUser($user)
            ->setCredential(
                (new UserCredentialDataProvider())->setHash($token->getAccessToken())
            )
            ;
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
     * @return \DataProvider\GithubAccessTokenResponseDataProvider
     */
    private function getTokenFromGithub(string $code): GithubAccessTokenResponseDataProvider
    {
        $token = new GithubAccessTokenRequestDataProvider();
        $token
            ->setCode($code)
            ->setRedirectUrl(
                $this->errorUrl->getUrl()
            )
        ;

        return $this->accessToken->getAccessToken($token);
    }

    /**
     * @param \DataProvider\GithubAccessTokenResponseDataProvider $token
     *
     * @return array
     */
    private function getEmailsFromGithub(GithubAccessTokenResponseDataProvider $token): array
    {
        $userRequest = new GithubRequestDataProvider();
        $userRequest
            ->setAccessToken($token->getAccessToken())
            ->setApiUrl('/user/emails')
        ;

        return $this->githubClient->getFromGithub($userRequest);
    }

    /**
     * @param \DataProvider\GithubAccessTokenResponseDataProvider $token
     * @param \DataProvider\UserDataProvider $newUser
     *
     * @return \DataProvider\UserDataProvider
     * @throws \Propel\Runtime\Exception\PropelException
     * @throws \Xervice\User\Business\Exception\UserException
     */
    private function createNewGithubUser(
        GithubAccessTokenResponseDataProvider $token,
        UserDataProvider $newUser
    ): UserDataProvider {
        $login = $this->getGithubLoginType($token);
        $newUser->addUserLogin($login);

        $user = $this->userFacade->createUser($newUser);
        return $user;
    }

    /**
     * @param \DataProvider\GithubAccessTokenResponseDataProvider $token
     *
     * @return \DataProvider\UserLoginDataProvider
     */
    private function getGithubLoginType(GithubAccessTokenResponseDataProvider $token): UserLoginDataProvider
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