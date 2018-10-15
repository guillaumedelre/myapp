<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 15/10/18
 * Time: 22:25
 */

namespace App\Security;

use App\Handler\LoginHandler;
use App\Redis\RedisWrapper;

class InternalRefreshToken
{
    /**
     * @var LoginHandler
     */
    private $loginHandler;

    /**
     * @var RedisWrapper
     */
    private $redisWrapper;

    /**
     * @var array
     */
    private $credentials;

    /**
     * @param LoginHandler $loginHandler
     * @param RedisWrapper $redisWrapper
     * @param string       $appUsername
     * @param string       $appPassword
     */
    public function __construct(
        LoginHandler $loginHandler,
        RedisWrapper $redisWrapper,
        string $appUsername,
        string $appPassword
    ) {
        $this->loginHandler = $loginHandler;
        $this->redisWrapper = $redisWrapper;
        $this->credentials = [
            'username' => $appUsername,
            'password' => $appPassword,
        ];
    }

    /**
     * @return array
     */
    public function getCredentials(): array
    {
        return $this->credentials;
    }

    /**
     * @return array
     */
    public function refresh(): array
    {
        $data = $this->loginHandler->handle($this->credentials);
        $this->redisWrapper->setAppToken($data['token']);

        return $data;
    }

}