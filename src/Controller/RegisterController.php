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
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    /**
     * @Route(name="register_index", path="/register")
     *
     * @return Response
     */
    public function index(Request $request, ClientHandler $clientHandler)
    {
        $data = [];

        if (Request::METHOD_POST === $request->getMethod()) {
            $route = 'login_index';
            try {
                $clientHandler->register($request->request->all());
            } catch (ConflictHttpException $e) {
                $route = 'register_index';
                $this->addFlash('error', 'The username already in use, please choose another one.');
            } catch (BadRequestHttpException $e) {
                $route = 'register_index';
                $this->addFlash('error', 'You must fill all the fields.');
            }
            return $this->redirectToRoute($route);
        }

        return $this->render('layouts/register.html.twig', $data);
    }

}