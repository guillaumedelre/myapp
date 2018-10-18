<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 14/10/18
 * Time: 14:33
 */

namespace App\Domain\Redis;


class Keys
{
    const USER_TOKEN = 'myapp:security:user:token';
    const USER_REFRESH_TOKEN = 'myapp:security:user:refresh_token';
}