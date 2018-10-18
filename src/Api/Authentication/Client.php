<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 16/10/18
 * Time: 09:01
 */

namespace App\Api\Authentication;

use GuzzleHttp as GuzzleHttp;
use Psr\Http\Message\ResponseInterface;

class Client
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
            ]
        );
    }

    /**
     * @param string $refreshToken
     *
     * @return ResponseInterface
     */
    public function refresh(string $refreshToken): ResponseInterface
    {
        return $this->httpClient->get("/refresh/$refreshToken");
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
                GuzzleHttp\RequestOptions::JSON => $data,
            ]
        );
    }

    /**
     * @param string $username
     *
     * @return ResponseInterface
     */
    public function findUsersByUsername(string $username): ResponseInterface
    {
        return $this->httpClient->get(
            '/api/users',
            [
                GuzzleHttp\RequestOptions::QUERY => ['username' => $username],
            ]
        );
    }
}