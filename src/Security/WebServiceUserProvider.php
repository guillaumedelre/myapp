<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 14/10/18
 * Time: 15:46
 */

namespace App\Security;


use App\Api\Authentication\ClientHandler;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class WebServiceUserProvider implements UserProviderInterface
{
    /**
     * @var ClientHandler
     */
    private $clientHandler;

    /**
     * WebServiceUserProvider constructor.
     *
     * @param ClientHandler $clientHandler
     */
    public function __construct(ClientHandler $clientHandler)
    {
        $this->clientHandler = $clientHandler;
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
        $data = $this->clientHandler->findUserByUsername($username);

        if (1 !== $data['hydra:totalItems'] ?? 0) {
            throw new UsernameNotFoundException(
                sprintf('Username "%s" does not exist.', $username)
            );
        }

        return new WebServiceUser(current($data['hydra:member'])['username'], '', '', []);
    }
}