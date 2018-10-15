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
use App\Security\InternalRefreshToken;
use Psr\Http\Message\RequestInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class InternalMiddleware
{
    /**
     * @var InternalRefreshToken
     */
    private $internalRefreshToken;

    /**
     * @var RedisWrapper
     */
    private $redisWrapper;

    /**
     * InternalMiddleware constructor.
     *
     * @param InternalRefreshToken $internalRefreshToken
     * @param RedisWrapper         $redisWrapper
     */
    public function __construct(InternalRefreshToken $internalRefreshToken, RedisWrapper $redisWrapper)
    {
        $this->internalRefreshToken = $internalRefreshToken;
        $this->redisWrapper = $redisWrapper;
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
        $token = $this->redisWrapper->getAppToken();
        if (!empty($token)) {
            return "Bearer $token";
        }

        $data = $this->internalRefreshToken->refresh();
        if (empty($data)) {
            throw new UnauthorizedHttpException('', "Application not found.");
        }

        return "Bearer {$data['token']}";
    }
}