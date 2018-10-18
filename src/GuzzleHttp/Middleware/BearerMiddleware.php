<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 14/10/18
 * Time: 15:27
 */

namespace App\GuzzleHttp\Middleware;

use App\Api\Authentication\ClientHandler;
use App\Domain\Http\Request\Headers;
use App\Redis\JwtStorage;
use Psr\Http\Message\RequestInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class BearerMiddleware
{
    /**
     * @var JwtStorage
     */
    private $jwtStorage;

    /**
     * @var ClientHandler
     */
    private $clientHandler;

    /**
     * BearerMiddleware constructor.
     *
     * @param JwtStorage    $jwtStorage
     * @param ClientHandler $clientHandler
     */
    public function __construct(JwtStorage $jwtStorage, ClientHandler $clientHandler)
    {
        $this->jwtStorage = $jwtStorage;
        $this->clientHandler = $clientHandler;
    }

    /**
     * @param callable $handler
     *
     * @return \Closure
     */
    public function __invoke(callable $handler)
    {
        return function (RequestInterface $request, array $options) use ($handler) {
            if (!$request->hasHeader(Headers::AUTHORIZATION)) {
                $request = $request->withHeader(Headers::AUTHORIZATION, $this->getAuthorization());
            }
            return $handler($request, $options);
        };
    }

    /**
     * @return string
     */
    private function getAuthorization(): string
    {
        $bearer = "Bearer";
        if (false === ($token = $this->jwtStorage->getUserToken())) {
            return $bearer;
        }

        return "$bearer $token";
    }
}