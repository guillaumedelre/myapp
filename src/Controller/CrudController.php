<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 19/10/18
 * Time: 20:44
 */

namespace App\Controller;


use App\Api\Authentication\ClientHandler;
use App\Domain\Api\ResourceEntrypoints;
use Doctrine\Common\Inflector\Inflector;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CrudController extends AbstractController
{
    /**
     * @Route(name="crud_index", path="/{resource}")
     *
     * @return Response
     */
    public function index(string $resource, ClientHandler $clientHandler)
    {
        $data = [
            'resource' => $resource,
            'entrypoint' => Inflector::pluralize($resource),
            'data' => $clientHandler->list($resource),
        ];

        return $this->render('layouts/crud.html.twig', $data);
    }

    /**
     * @Route(name="crud_create", path="/{resource}/create")
     *
     * @return Response
     */
    public function create(string $resource, ClientHandler $clientHandler)
    {
        $data = [
            'resource' => $resource,
            'entrypoint' => Inflector::pluralize($resource),
            'data' => $clientHandler->list($resource),
        ];

        return $this->render('layouts/crud.html.twig', $data);
    }

    /**
     * @Route(name="crud_edit", path="/{resource}/{id}/edit")
     *
     * @return Response
     */
    public function edit(string $resource, ClientHandler $clientHandler)
    {
        $data = [
            'resource' => $resource,
            'entrypoint' => Inflector::pluralize($resource),
            'data' => $clientHandler->list($resource),
        ];

        return $this->render('layouts/crud.html.twig', $data);
    }

    /**
     * @Route(name="crud_show", path="/{resource}/{id}/show")
     *
     * @return Response
     */
    public function show(string $resource, ClientHandler $clientHandler)
    {
        $data = [
            'resource' => $resource,
            'entrypoint' => Inflector::pluralize($resource),
            'data' => $clientHandler->list($resource),
        ];

        return $this->render('layouts/crud.html.twig', $data);
    }

    /**
     * @Route(name="crud_delete", path="/{resource}/{id}/delete")
     *
     * @return Response
     */
    public function delete(string $resource, ClientHandler $clientHandler)
    {
        $data = [
            'resource' => $resource,
            'entrypoint' => Inflector::pluralize($resource),
            'data' => $clientHandler->list($resource),
        ];

        return $this->render('layouts/crud.html.twig', $data);
    }

}