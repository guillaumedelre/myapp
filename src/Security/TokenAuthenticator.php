<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 14/10/18
 * Time: 16:00
 */

namespace App\Security;

use App\Domain\Http\Request\Headers;
use App\Redis\JwtStorage;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Token;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class TokenAuthenticator extends AbstractGuardAuthenticator
{
    /**
     * @var JwtStorage
     */
    private $jwtStorage;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * TokenAuthenticator constructor.
     *
     * @param JwtStorage      $jwtStorage
     * @param RouterInterface $router
     */
    public function __construct(JwtStorage $jwtStorage, RouterInterface $router)
    {
        $this->jwtStorage = $jwtStorage;
        $this->router = $router;
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning false will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request)
    {
        return !empty($this->jwtStorage->getUserToken());
    }

    /**
     * Called on every request. Return whatever credentials you want to
     * be passed to getUser() as $credentials.
     */
    public function getCredentials(Request $request)
    {
        return array(
            'token' => $this->jwtStorage->getUserToken(),
        );
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        /** @var Token $token */
        $token = (new Parser())->parse($credentials['token'] ?? '');

        if (empty($token->getClaims()['username'])) {
            return null;
        }

        return $userProvider->loadUserByUsername($token->getClaims()['username']);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        // check credentials - e.g. make sure the password is valid
        // no credential check is needed in this case

        // return true to cause authentication success
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        // on success, let the request continue
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data = array(
            'message' => strtr($exception->getMessageKey(), $exception->getMessageData())

            // or to translate this message
            // $this->translator->trans($exception->getMessageKey(), $exception->getMessageData())
        );

        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    /**
     * Called when authentication is needed, but it's not sent
     */
    public function start(Request $request, AuthenticationException $authException = null)
    {
//        $data = array(
//            // you might translate this message
//            'message' => 'Authentication Required'
//        );
//
//        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
        return new RedirectResponse($this->router->generate('login_index'));
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
