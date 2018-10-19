<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 16/10/18
 * Time: 09:14
 */

namespace App\Api\Authentication;


use App\Domain\Api\ResourceEntrypoints;
use App\Domain\Exception\InvalidResourceEndpointException;
use App\Domain\Exception\RetryHttpException;
use App\Domain\Http\Request\Headers;
use App\Redis\ApiDocStorage;
use App\Redis\JwtStorage;
use Doctrine\Common\Inflector\Inflector;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Exception\TransferException;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class ClientHandler
{
    /**
     * @var array
     */
    private static $resourceEntrypoints = [
        ResourceEntrypoints::APPLICATION,
        ResourceEntrypoints::PERMISSION,
        ResourceEntrypoints::PRIVILEGE,
        ResourceEntrypoints::ROLE,
        ResourceEntrypoints::USER,
    ];

    /**
     * @var array
     */
    private static $retryables = [
        Response::HTTP_GATEWAY_TIMEOUT,
        Response::HTTP_SERVICE_UNAVAILABLE,
        Response::HTTP_UNPROCESSABLE_ENTITY,
        498,
    ];

    /**
     * @var Client
     */
    private $apiClient;

    /**
     * @var JsonEncoder
     */
    private $jsonEncoder;

    /**
     * @var JwtStorage
     */
    private $jwtStorage;

    /**
     * @var ApiDocStorage
     */
    private $apiDocStorage;

    /**
     * RegisterHandler constructor.
     *
     * @param Client        $apiClient
     * @param JsonEncoder   $jsonEncoder
     * @param JwtStorage    $jwtStorage
     * @param ApiDocStorage $apiDocStorage
     */
    public function __construct(
        Client $apiClient,
        JsonEncoder $jsonEncoder,
        JwtStorage $jwtStorage,
        ApiDocStorage $apiDocStorage
    ) {
        $this->apiClient = $apiClient;
        $this->jsonEncoder = $jsonEncoder;
        $this->jwtStorage = $jwtStorage;
        $this->apiDocStorage = $apiDocStorage;
    }

    /**
     * @return array
     */
    public function doc(): array
    {
        /** @var ResponseInterface $response */
        $response = $this->apiClient->doc();
        $this->apiDocStorage->setApiDocAuthentication($response->getBody()->getContents());
        $data = $this->jsonEncoder->decode($this->apiDocStorage->getApiDocAuthentication(), JsonEncoder::FORMAT);

        return $data;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function login(array $data): array
    {
        /** @var ResponseInterface $response */
        $response = $this->apiClient->login($data);
        $data = $this->jsonEncoder->decode($response->getBody()->getContents(), JsonEncoder::FORMAT);
        $this->jwtStorage->setUserToken($data['token']);
        $this->jwtStorage->setUserRefreshToken(current($response->getHeader(Headers::X_REFRESH_TOKEN)));

        return $data;
    }

    /**
     * @param string $resource
     * @param array  $filters
     *
     * @return array
     *
     * @throws InvalidResourceEndpointException
     */
    public function list(string $resource, array $filters = []): array
    {
        $data = [];

        if (!in_array($resource, self::$resourceEntrypoints)) {
            throw new InvalidResourceEndpointException();
        }

        try {

            /** @var ResponseInterface $response */
            $response = $this->apiClient->list(Inflector::pluralize($resource), $filters);
            $data = $this->jsonEncoder->decode($response->getBody()->getContents(), JsonEncoder::FORMAT);

        } catch (RequestException $e) {

            if ($e->hasResponse() && in_array($e->getResponse()->getStatusCode(), self::$retryables)) {
                try {
                    $this->refresh();
                } catch (RetryHttpException $e) {
                    // log the retry ?
                }

                $data = $this->list($resource, $filters);
            }
        }

        return $data;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function register(array $data): array
    {
        /** @var ResponseInterface $response */
        $response = $this->apiClient->register($data);
        $data = $this->jsonEncoder->decode($response->getBody()->getContents(), JsonEncoder::FORMAT);

        return $data;
    }

    /**
     * @param string $username
     *
     * @return array
     */
    public function findUserByUsername(string $username): array
    {
        $data = [];

        try {

            /** @var ResponseInterface $response */
            $response = $this->apiClient->findUsersByUsername($username);
            $data = $this->jsonEncoder->decode($response->getBody()->getContents(), JsonEncoder::FORMAT);

        } catch (RequestException $e) {

            if ($e->hasResponse() && in_array($e->getResponse()->getStatusCode(), self::$retryables)) {
                try {
                    $this->refresh();
                } catch (RetryHttpException $e) {
                    // log the retry ?
                }

                $data = $this->findUserByUsername($username);
            }
        }

        return $data;
    }

    /**
     * @return array
     * @throws RetryHttpException
     */
    public function refresh(): array
    {
        try {
            /** @var ResponseInterface $response */
            $response = $this->apiClient->refresh($this->jwtStorage->getUserRefreshToken());
            $data = $this->jsonEncoder->decode($response->getBody()->getContents(), JsonEncoder::FORMAT);
            $this->jwtStorage->setUserToken($data['token']);
            $this->jwtStorage->setUserRefreshToken(current($response->getHeader(Headers::X_REFRESH_TOKEN)));
        } catch (TransferException $e) {
            $this->jwtStorage->removeUserToken();
            $this->jwtStorage->removeUserRefreshToken();
            throw new RetryHttpException();
        }

        return $data;
    }
}