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

class JwtStorage
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
     * @param string $bearer
     */
    public function setUserRefreshToken(string $bearer)
    {
        $this->redisClient->set(Keys::USER_REFRESH_TOKEN, $bearer);
    }

    /**
     * @return bool|mixed|string
     */
    public function getUserToken()
    {
        return $this->redisClient->get(Keys::USER_TOKEN);
    }

    /**
     * @return bool|mixed|string
     */
    public function getUserRefreshToken()
    {
        return $this->redisClient->get(Keys::USER_REFRESH_TOKEN);
    }

    /**
     * @return int|mixed
     */
    public function removeUserToken()
    {
        return $this->redisClient->del(Keys::USER_TOKEN);
    }

    /**
     * @return int|mixed
     */
    public function removeUserRefreshToken()
    {
        return $this->redisClient->del(Keys::USER_REFRESH_TOKEN);
    }
}