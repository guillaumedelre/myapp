<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 18/10/18
 * Time: 21:25
 */

namespace App\Domain\Api;

class ResourceEntrypoints
{
    const APPLICATION = 'application';
    const PERMISSION = 'permission';
    const PRIVILEGE = 'privilege';
    const ROLE = 'role';
    const USER = 'user';

    /**
     * @return array
     */
    public static function toArray()
    {
        return [
            self::APPLICATION,
            self::PERMISSION,
            self::PRIVILEGE,
            self::ROLE,
            self::USER,
        ];
    }
}