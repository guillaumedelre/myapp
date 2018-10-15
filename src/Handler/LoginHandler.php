<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 13/10/18
 * Time: 16:29
 */

namespace App\Handler;


use App\Api\Authentication\UnsecuredClient;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class LoginHandler
{
    /**
     * @var UnsecuredClient
     */
    private $unsecuredClient;

    /**
     * @var JsonEncoder
     */
    private $jsonEncoder;

    /**
     * RegisterHandler constructor.
     *
     * @param UnsecuredClient $unsecuredClient
     * @param JsonEncoder     $jsonEncoder
     */
    public function __construct(UnsecuredClient $unsecuredClient, JsonEncoder $jsonEncoder)
    {
        $this->unsecuredClient = $unsecuredClient;
        $this->jsonEncoder = $jsonEncoder;
    }

    /**
     * @param array $data
     *
     * @return array
     */
    public function handle(array $data): array
    {
        /** @var ResponseInterface $response */
        $response = $this->unsecuredClient->login($data);

        if (Response::HTTP_OK !== $response->getStatusCode()) {
            throw new UnauthorizedHttpException('', $response->getReasonPhrase());
        }
        $response->getBody()->rewind();
        return $this->jsonEncoder->decode($response->getBody()->getContents(), JsonEncoder::FORMAT);
    }
}