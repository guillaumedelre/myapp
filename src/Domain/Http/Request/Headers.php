<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 14/10/18
 * Time: 13:31
 */

namespace App\Domain\Http\Request;


class Headers
{
    const AUTHORIZATION = 'Authorization';
    const ACCEPT = 'Accept';
    const CONTENT_TYPE = 'Content-Type';

    const X_REFRESH_TOKEN = 'X-Refresh-Token';
}