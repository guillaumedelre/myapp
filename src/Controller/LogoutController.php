<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 23/09/18
 * Time: 09:52
 */

namespace App\Controller;

use App\Redis\JwtStorage;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogoutController extends AbstractController
{
    /**
     * @Route(name="logout_index", path="/logout")
     *
     * @return Response
     */
    public function index(Request $request, JwtStorage $jwtStorage)
    {
        $jwtStorage->removeUserToken();
        $jwtStorage->removeUserRefreshToken();

        return $this->render('layouts/login.html.twig', []);
    }
}