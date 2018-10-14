<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 14/10/18
 * Time: 15:27
 */

namespace App\GuzzleHttp\Middleware;

use App\Domain\Http\Request\Headers;
use App\Handler\LoginHandler;
use App\Redis\RedisWrapper;
use Psr\Http\Message\RequestInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class JwtMiddleware
{
    /**
     * @var RedisWrapper
     */
    private $redisWrapper;

    /**
     * @var LoginHandler
     */
    private $loginHandler;

    /**
     * InternalMiddleware constructor.
     *
     * @param RedisWrapper $redisWrapper
     * @param LoginHandler $loginHandler
     */
    public function __construct(
        RedisWrapper $redisWrapper,
        LoginHandler $loginHandler
    ) {
        $this->redisWrapper = $redisWrapper;
        $this->loginHandler = $loginHandler;
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

    private function getAuthorization()
    {
        $token = $this->redisWrapper->getUserToken();
        if (!empty($token)) {
            return "Bearer $token";
        }

        throw new UnauthorizedHttpException('', "You must login again.");
    }
}