<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 14/10/18
 * Time: 15:04
 */

namespace App\Api\Authentication;

use GuzzleHttp as GuzzleHttp;
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
     * UnsecuredClient constructor.
     *
     * @param GuzzleHttp\Client $httpClient
     * @param JsonEncoder $jsonEncoder
     */
    public function __construct(GuzzleHttp\Client $httpClient, JsonEncoder $jsonEncoder)
    {
        $this->httpClient = $httpClient;
        $this->jsonEncoder = $jsonEncoder;
    }

    /**
     * @param string $username
     *
     * @return array
     */
    public function findUsersByUsername(string $username): array
    {
        $response = $this->httpClient->get(
            '/api/users',
            [
                GuzzleHttp\RequestOptions::QUERY => [
                    'username' => $username
                ],
                GuzzleHttp\RequestOptions::HTTP_ERRORS => false,
            ]
        );

        $responseData = $this->jsonEncoder->decode($response->getBody()->getContents(), JsonEncoder::FORMAT);

        if (1 !== $responseData['hydra:totalItems']) {
            return [];
        }

        return current($responseData['hydra:member']);
    }

}