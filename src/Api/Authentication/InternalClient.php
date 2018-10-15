<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 14/10/18
 * Time: 15:04
 */

namespace App\Api\Authentication;

use App\Security\InternalRefreshToken;
use GuzzleHttp as GuzzleHttp;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class InternalClient
{
    /**
     * @var GuzzleHttp\Client
     */
    private $httpClient;

    /**
     * @var JsonEncoder
     */
    private $jsonEncoder;

    /**
     * @var InternalRefreshToken
     */
    private $internalRefreshToken;

    /**
     * InternalClient constructor.
     *
     * @param GuzzleHttp\Client $httpClient
     * @param JsonEncoder $jsonEncoder
     * @param InternalRefreshToken $internalRefreshToken
     */
    public function __construct(
        GuzzleHttp\Client $httpClient,
        JsonEncoder $jsonEncoder,
        InternalRefreshToken $internalRefreshToken
    ) {
        $this->httpClient = $httpClient;
        $this->jsonEncoder = $jsonEncoder;
        $this->internalRefreshToken = $internalRefreshToken;
    }

    /**
     * @param string $username
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function findUsersByUsername(string $username): ResponseInterface
    {
        $response = $this->httpClient->get(
            '/api/users',
            [
                GuzzleHttp\RequestOptions::QUERY       => ['username' => $username],
                GuzzleHttp\RequestOptions::HTTP_ERRORS => false,
            ]
        );

        if ($response->getStatusCode() === 498) {
            $this->internalRefreshToken->refresh();
            return $this->findUsersByUsername($username);
        } else {
            return $response;
        }
    }

}