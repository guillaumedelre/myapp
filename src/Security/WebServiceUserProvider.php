<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 14/10/18
 * Time: 15:46
 */

namespace App\Security;


use App\Api\Authentication\InternalClient;
use GuzzleHttp\Exception\ClientException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AuthenticationExpiredException;
use Symfony\Component\Security\Core\Exception\TokenNotFoundException;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Serializer\Encoder\JsonEncoder;

class WebServiceUserProvider implements UserProviderInterface
{
    /**
     * @var InternalClient
     */
    private $internalClient;

    /**
     * @var JsonEncoder
     */
    private $jsonEncoder;

    /**
     * WebServiceUserProvider constructor.
     *
     * @param InternalClient $internalClient
     * @param JsonEncoder    $jsonEncoder
     */
    public function __construct(InternalClient $internalClient, JsonEncoder $jsonEncoder)
    {
        $this->internalClient = $internalClient;
        $this->jsonEncoder = $jsonEncoder;
    }

    public function loadUserByUsername($username)
    {
        return $this->fetchUser($username);
    }

    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof WebserviceUser) {
            throw new UnsupportedUserException(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }

        $username = $user->getUsername();

        return $this->fetchUser($username);
    }

    public function supportsClass($class)
    {
        return WebserviceUser::class === $class;
    }

    private function fetchUser($username)
    {
        // make a call to your webservice here
        $response = $this->internalClient->findUsersByUsername($username);
        $responseData = $this->jsonEncoder->decode($response->getBody()->getContents(), JsonEncoder::FORMAT);

        if ($response->getStatusCode() === 498) {
            throw new UsernameNotFoundException(
                $responseData['message']
            );
        }

        if (1 !== $responseData['hydra:totalItems']) {
            throw new UsernameNotFoundException(
                sprintf('Username "%s" does not exist.', $username)
            );
        }

        return new WebServiceUser(current($responseData['hydra:member'])['username'], '', '', []);
    }
}