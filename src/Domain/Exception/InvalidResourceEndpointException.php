<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 18/10/18
 * Time: 08:08
 */

namespace App\Domain\Exception;

use GuzzleHttp\Exception\TransferException;

class InvalidResourceEndpointException extends TransferException
{

}