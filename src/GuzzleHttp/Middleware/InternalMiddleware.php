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

class InternalMiddleware
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
     * @var array
     */
    private $credentials;

    /**
     * InternalMiddleware constructor.
     *
     * @param RedisWrapper $redisWrapper
     * @param LoginHandler $loginHandler
     * @param string       $appUsername
     * @param string       $appPassword
     */
    public function __construct(
        RedisWrapper $redisWrapper,
        LoginHandler $loginHandler,
        string $appUsername,
        string $appPassword
    ) {
        $this->redisWrapper = $redisWrapper;
        $this->loginHandler = $loginHandler;
        $this->credentials = [
            'username' => $appUsername,
            'password' => $appPassword,
        ];
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

        $data = $this->loginHandler->handle($this->credentials);
        if (empty($data)) {
            throw new UnauthorizedHttpException('', "Application {$this->credentials['username']} not found.");
        }

        return "Bearer {$data['token']}";
    }
}