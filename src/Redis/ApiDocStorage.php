<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 13/10/18
 * Time: 18:58
 */

namespace App\Redis;

use App\Domain\Redis\Keys;
use Doctrine\Common\Inflector\Inflector;
use Snc\RedisBundle\Client\Phpredis as Phpredis;

class ApiDocStorage
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
     * @param string $apiDoc
     */
    public function setApiDocAuthentication(string $apiDoc)
    {
        $this->redisClient->set(Keys::API_DOC_AUTHENTICATION, $apiDoc);
    }

    /**
     * @return bool|mixed|string
     */
    public function getApiDocAuthentication()
    {
        return $this->redisClient->get(Keys::API_DOC_AUTHENTICATION);
    }
}