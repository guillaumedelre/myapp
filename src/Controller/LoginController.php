<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 23/09/18
 * Time: 09:52
 */

namespace App\Controller;


use App\Handler\LoginHandler;
use App\Redis\RedisWrapper;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    /**
     * @Route("login_index")
     * @return Response
     */
    public function index(Request $request, RedisWrapper $redisWrapper, LoginHandler $loginHandler)
    {
        $data = [];

        if (Request::METHOD_POST === $request->getMethod()) {
            try {
                $route = 'default_index';
                $data = $loginHandler->handle($request->request->all());
                $redisWrapper->setUserToken($data['token']);

            } catch (UnauthorizedHttpException $e) {
                $route = 'login_index';
                $this->addFlash('error', $e->getMessage());
            }
            return $this->redirectToRoute($route);
        }

        return $this->render('layouts/login.html.twig', $data);
    }
}