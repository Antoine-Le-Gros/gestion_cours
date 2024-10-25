<?php

namespace App\Security;

use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\RememberMeBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\SecurityRequestAttributes;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class LoginAuthenticator extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private AuthorizationCheckerInterface $authorizationChecker;

    public function __construct(private UrlGeneratorInterface $urlGenerator, private UserRepository $userRepository, AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    public function authenticate(Request $request): Passport
    {
        $identifier = $request->request->get('username');

        $request->getSession()->set(SecurityRequestAttributes::LAST_USERNAME, $identifier);

        $user = $this->userRepository->findOneByLoginOrEmail($identifier);

        if (!$user) {
            throw new AuthenticationException('Identifiant ou mot de passe incorrect');
        }

        return new Passport(
            new UserBadge($user->getUserIdentifier()),
            new PasswordCredentials($request->request->get('password')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                new RememberMeBadge(),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($this->authorizationChecker->isGranted('ROLE_ADMIN')) {
            return new RedirectResponse($this->urlGenerator->generate('app_home_admin'));
        }

        return new RedirectResponse($this->urlGenerator->generate('/'));
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }

    /**
     * Override to control what happens when the user hits a secure page
     * but isn't logged in yet.
     *
     * @throws HttpException
     */
    public function start(Request $request, ?AuthenticationException $authException = null): RedirectResponse
    {
        /** @var string $route current route */
        $route = $request->get('_route');
        // API route ?
        if (str_starts_with($route, '_api_')) {
            throw new HttpException(Response::HTTP_UNAUTHORIZED);
        }
        $url = $this->getLoginUrl($request);

        return new RedirectResponse($url);
    }
}
