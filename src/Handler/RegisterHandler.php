<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 13/10/18
 * Time: 16:29
 */

namespace App\Handler;


use App\Api\Authentication\UnsecuredClient;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class RegisterHandler
{
    /**
     * @var UnsecuredClient
     *
     */
    private $unsecuredClient;

    /**
     * RegisterHandler constructor.
     *
     * @param UnsecuredClient $unsecuredClient
     */
    public function __construct(UnsecuredClient $unsecuredClient)
    {
        $this->unsecuredClient = $unsecuredClient;
    }

    /**
     * @param array $data
     */
    public function handle(array $data)
    {
        /** @var ResponseInterface $response */
        $response = $this->unsecuredClient->register($data);

        if (Response::HTTP_CONFLICT === $response->getStatusCode()) {
            throw new ConflictHttpException();
        }

        if (Response::HTTP_BAD_REQUEST === $response->getStatusCode()) {
            throw new BadRequestHttpException();
        }
    }
}