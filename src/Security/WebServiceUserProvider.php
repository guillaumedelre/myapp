<?php
/**
 * Created by PhpStorm.
 * User: gdelre
 * Date: 14/10/18
 * Time: 15:46
 */

namespace App\Security;


use App\Api\Authentication\InternalClient;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class WebServiceUserProvider implements UserProviderInterface
{
    /**
     * @var InternalClient
     */
    private $internalClient;

    /**
     * WebserviceUserProvider constructor.
     *
     * @param InternalClient $internalClient
     */
    public function __construct(InternalClient $internalClient)
    {
        $this->internalClient = $internalClient;
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
        $userData = $this->internalClient->findUsersByUsername($username);
        // pretend it returns an array on success, false if there is no user

        if ($userData) {
            return new WebServiceUser($userData['username'], '', '', []);
        }

        throw new UsernameNotFoundException(
            sprintf('Username "%s" does not exist.', $username)
        );
    }
}