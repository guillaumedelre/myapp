<?php

namespace App\Twig;

use App\Redis\JwtStorage;
use Lcobucci\JWT\Parser;
use Lcobucci\JWT\Token;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class JwtExtension extends AbstractExtension
{
    /**
     * @var Token
     */
    private $token;

    /**
     * JwtExtension constructor.
     *
     * @param JwtStorage $jwtStorage
     */
    public function __construct(JwtStorage $jwtStorage)
    {
        try {
            $this->token = (new Parser())->parse($jwtStorage->getUserToken());
        } catch (\InvalidArgumentException $e) {
            $this->token = null;
        }
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('tokenUsername', [$this, 'tokenUsername']),
            new TwigFunction('tokenRoles', [$this, 'tokenRoles']),
        ];
    }

    public function tokenUsername()
    {
        return $this->token->getClaim('username') ?? '';
    }

    public function tokenRoles()
    {
        return current($this->token->getClaim('roles')) ?? '';
    }
}
