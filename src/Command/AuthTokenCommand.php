<?php

namespace App\Command;

use App\Handler\LoginHandler;
use App\Handler\RegisterHandler;
use App\Redis\RedisWrapper;
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
     * @var LoginHandler
     */
    private $loginHandler;

    /**
     * @var RedisWrapper
     */
    private $redisWrapper;

    /**
     * @var array
     */
    private $credentials;

    /**
     * RegisterCommand constructor.
     *
     * @param LoginHandler    $loginHandler
     * @param RedisWrapper    $redisWrapper
     * @param string          $appUsername
     * @param string          $appPassword
     */
    public function __construct(
        LoginHandler $loginHandler,
        RedisWrapper $redisWrapper,
        string $appUsername,
        string $appPassword
    ) {
        parent::__construct(self::$defaultName);

        $this->loginHandler = $loginHandler;
        $this->redisWrapper = $redisWrapper;
        $this->credentials = [
            'username' => $appUsername,
            'password' => $appPassword,
        ];
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
            $data = $this->loginHandler->handle($this->credentials);
            $this->redisWrapper->setAppToken($data['token']);
            $io->success("Application {$this->credentials['username']} received its access token.");
        } catch (UnauthorizedHttpException $e) {
            $io->error("Application {$this->credentials['username']} not found.");
            return 1;
        }

        return 0;
    }
}
