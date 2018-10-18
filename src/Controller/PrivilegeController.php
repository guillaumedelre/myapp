<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 23/09/18
 * Time: 09:52
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PrivilegeController extends AbstractController
{
    /**
     * @Route(name="privilege_index", path="/privileges")
     *
     * @return Response
     */
    public function index()
    {
        $data = [];

        return $this->render('layouts/blank.html.twig', $data);
    }
}