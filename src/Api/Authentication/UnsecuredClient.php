<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 14/10/18
 * Time: 15:04
 */

namespace App\Api\Authentication;

use GuzzleHttp as GuzzleHttp;
use Psr\Http\Message\ResponseInterface;

class UnsecuredClient
{
    /**
     * @var GuzzleHttp\Client
     */
    private $httpClient;

    /**
     * UnsecuredClient constructor.
     *
     * @param GuzzleHttp\Client $httpClient
     */
    public function __construct(GuzzleHttp\Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    /**
     * @param array $data
     *
     * @return ResponseInterface
     */
    public function register(array $data = []): ResponseInterface
    {
        return $this->httpClient->post(
            '/register',
            [
                GuzzleHttp\RequestOptions::FORM_PARAMS => $data,
                GuzzleHttp\RequestOptions::HTTP_ERRORS => false,
            ]
        );
    }

    /**
     * @param array $data
     *
     * @return ResponseInterface
     */
    public function login(array $data = []): ResponseInterface
    {
        return $this->httpClient->post(
            '/token',
            [
                GuzzleHttp\RequestOptions::JSON        => $data,
                GuzzleHttp\RequestOptions::HTTP_ERRORS => false,
            ]
        );
    }
}