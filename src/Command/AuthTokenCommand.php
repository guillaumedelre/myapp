<?php

namespace App\Command;

use App\Handler\LoginHandler;
use App\Handler\RegisterHandler;
use App\Redis\RedisWrapper;
use App\Security\InternalRefreshToken;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthTokenCommand extends Command
{
    protected static $defaultName = 'app:auth:token';

    /**
     * @var InternalRefreshToken
     */
    private $internalRefreshToken;

    /**
     * RegisterCommand constructor.
     *
     * @param InternalRefreshToken $internalRefreshToken
     */
    public function __construct(InternalRefreshToken $internalRefreshToken) {
        parent::__construct(self::$defaultName);

        $this->internalRefreshToken = $internalRefreshToken;
    }

    protected function configure()
    {
        $this
            ->setDescription('Run this command to get an access token for the application from authentication service.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        try {
            $this->internalRefreshToken->refresh();
            $io->success("Application {$this->internalRefreshToken->getCredentials()['username']} received its access token.");
        } catch (UnauthorizedHttpException $e) {
            $io->error("Application {$this->internalRefreshToken->getCredentials()['username']} not found.");
            return 1;
        }

        return 0;
    }
}
