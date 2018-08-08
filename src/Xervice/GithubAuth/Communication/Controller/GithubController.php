<?php


namespace Xervice\GithubAuth\Communication\Controller;


use DataProvider\GithubAuthRequestDataProvider;
use DataProvider\LogMessageDataProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Xervice\Controller\Business\Controller\AbstractController;
use Xervice\GithubAuth\GithubAuthConfig;

/**
 * @method \Xervice\GithubAuth\GithubAuthFacade getFacade()
 * @method \Xervice\GithubAuth\GithubAuthFactory getFactory()
 */
class GithubController extends AbstractController
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \InvalidArgumentException
     * @throws \Core\Locator\Dynamic\ServiceNotParseable
     */
    public function githubLoginAction(): Response
    {
        return new RedirectResponse(
            $this->getFacade()->getGithubAuthUrl('read:user,user:email')
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \InvalidArgumentException
     * @throws \Core\Locator\Dynamic\ServiceNotParseable
     * @throws \Xervice\User\Business\Exception\UserException
     */
    public function githubAuthAction(Request $request): Response
    {
        $this->getFactory()->getUserFacade()->login(
            $this->getFactory()->createGithubAuth()->createUserFromGithub($request)
        );

        return new RedirectResponse(GithubAuthConfig::AFTER_LOGIN_PATH);
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Core\Locator\Dynamic\ServiceNotParseable
     */
    public function githubError(Request $request): Response
    {
        $message = new LogMessageDataProvider();
        $message
            ->setTitle('Github Login failed')
            ->setMessage($request->query->get('error'));

        $this->getFactory()->getLoggerFacade()->log($message);

        return new RedirectResponse(
            GithubAuthConfig::AFTER_ERROR_PATH . '?error=' . $request->query->get('error')
        );
    }
}