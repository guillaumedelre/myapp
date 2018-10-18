<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 23/09/18
 * Time: 09:52
 */

namespace App\Controller;

use App\Api\Authentication\ClientHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    /**
     * @Route(name="login_index", path="/login")
     *
     * @return Response
     */
    public function index(Request $request, ClientHandler $clientHandler)
    {
        $data = [];

        if (Request::METHOD_POST === $request->getMethod()) {
            try {
                $route = 'default_index';
                $clientHandler->login($request->request->all());

            } catch (UnauthorizedHttpException $e) {
                $route = 'login_index';
                $this->addFlash('error', $e->getMessage());
            }
            return $this->redirectToRoute($route);
        }

        return $this->render('layouts/login.html.twig', $data);
    }
}