GithubAuth
=====================


Installation
-----------------
```
composer require xervice/github-oath
```

Configuration
------------------
* You must add the controller to your routing
* You must add the GithubLogin class to the UserDependencyProvider::getLoginPluginList

```php
$this->addRoute(GithubAuthConfig::LOGIN_PATH, GithubController::class, 'githubLoginAction', ['GET']);
$this->addRoute(GithubAuthConfig::AUTH_PATH, GithubController::class, 'githubAuthAction', ['GET']);
$this->addRoute(GithubAuthConfig::ERROR_PATH, GithubController::class, 'githubError', ['GET']);

protected function addRoute(string $path, string $controller, string $action, array $methods);
```


Using
-----------------
You must add the GithubController to your routing. The suggested paths are configured in the GithubAuthConfig.