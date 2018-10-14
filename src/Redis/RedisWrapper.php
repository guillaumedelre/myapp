<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 13/10/18
 * Time: 18:58
 */

namespace App\Redis;

use App\Domain\Redis\Keys;
use Snc\RedisBundle\Client\Phpredis as Phpredis;

class RedisWrapper
{
    /**
     * @var Phpredis\Client
     */
    private $redisClient;

    /**
     * RedisWrapper constructor.
     *
     * @param Phpredis\Client $redisClient
     */
    public function __construct(Phpredis\Client $redisClient)
    {
        $this->redisClient = $redisClient;
    }

    /**
     * @param string $bearer
     */
    public function setUserToken(string $bearer)
    {
        $this->redisClient->set(Keys::USER_TOKEN, $bearer);
    }


    /**
     * @return bool|mixed|string
     */
    public function getUserToken()
    {
        return $this->redisClient->get(Keys::USER_TOKEN);
    }

    /**
     * @param string $bearer
     */
    public function setAppToken(string $bearer)
    {
        $this->redisClient->set(Keys::APP_TOKEN, $bearer);
    }

    /**
     * @return bool|mixed|string
     */
    public function getAppToken()
    {
        return $this->redisClient->get(Keys::APP_TOKEN);
    }
}