<?php


namespace Xervice\GithubAuth\Communication\Controller;


use DataProvider\GithubAuthRequestDataProvider;
use DataProvider\LogMessageDataProvider;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Xervice\Controller\Business\Controller\AbstractController;
use Xervice\GithubAuth\Business\Exception\GithubException;
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
            $this->getFacade()->getGithubAuthUrl('read:user,user:email')->getUrl()
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
        if ($request->query->has('code')) {
            $redirect = $this->createUserFromGithub($request);
        } else {
            $redirect = $this->createErrorRedirect();
        }

        return $redirect;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \InvalidArgumentException
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

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \InvalidArgumentException
     * @throws \Core\Locator\Dynamic\ServiceNotParseable
     * @throws \Xervice\User\Business\Exception\UserException
     */
    private function createUserFromGithub(Request $request): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $redirect = new RedirectResponse(GithubAuthConfig::AFTER_LOGIN_PATH);

        try {
            $this->getFactory()->getUserFacade()->login(
                $this->getFactory()->createGithubAuth()->createUserFromGithub($request->query->get('code'))
            );
        } catch (GithubException $exception) {
            $message = new LogMessageDataProvider();
            $message
                ->setTitle($exception->getMessage())
                ->setContext($exception->getCode());
            $this->getFactory()->getLoggerFacade()->log($message);

            $redirect = new RedirectResponse(GithubAuthConfig::AFTER_ERROR_PATH . '?error=1');
        }
        return $redirect;
}

    /**
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @throws \InvalidArgumentException
     * @throws \Core\Locator\Dynamic\ServiceNotParseable
     */
    private function createErrorRedirect(): \Symfony\Component\HttpFoundation\RedirectResponse
    {
        $message = new LogMessageDataProvider();
        $message
            ->setTitle('Github not auth from code');
        $this->getFactory()->getLoggerFacade()->log($message);

        $redirect = new RedirectResponse(GithubAuthConfig::AFTER_ERROR_PATH . '?error=1');
        return $redirect;
    }
}