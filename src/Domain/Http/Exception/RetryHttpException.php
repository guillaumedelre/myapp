<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 18/10/18
 * Time: 08:08
 */

namespace App\Domain\Http\Exception;

use GuzzleHttp\Exception\TransferException;

class RetryHttpException extends TransferException
{

}