<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 18/10/18
 * Time: 15:30
 */

namespace App\Security;

use App\Redis\JwtStorage;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;

class LogoutSuccessHandler implements LogoutSuccessHandlerInterface
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
     * LogoutSuccessHandler constructor.
     *
     * @param JwtStorage      $jwtStorage
     * @param RouterInterface $router
     */
    public function __construct(JwtStorage $jwtStorage, RouterInterface $router)
    {
        $this->jwtStorage = $jwtStorage;
        $this->router = $router;
    }

    public function onLogoutSuccess(Request $request)
    {
        $this->jwtStorage->removeUserToken();
        $this->jwtStorage->removeUserRefreshToken();

        return new RedirectResponse($this->router->generate('login_index'));
    }

}