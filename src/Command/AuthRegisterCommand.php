<?php

namespace App\Command;

use App\Handler\RegisterHandler;
use App\Redis\RedisWrapper;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;

class AuthRegisterCommand extends Command
{
    protected static $defaultName = 'app:auth:register';

    /**
     * @var RegisterHandler
     */
    private $registerHandler;

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
     * @param RegisterHandler $registerHandler
     * @param RedisWrapper    $redisWrapper
     * @param string          $appUsername
     * @param string          $appPassword
     */
    public function __construct(
        RegisterHandler $registerHandler,
        RedisWrapper $redisWrapper,
        string $appUsername,
        string $appPassword
    ) {
        parent::__construct(self::$defaultName);

        $this->registerHandler = $registerHandler;
        $this->redisWrapper = $redisWrapper;
        $this->credentials = [
            'username' => $appUsername,
            'password' => $appPassword,
        ];
    }

    protected function configure()
    {
        $this
            ->setDescription('Run this command to register the application on authentication service.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $return = 0;
        $io = new SymfonyStyle($input, $output);

        try {
            $this->registerHandler->handle($this->credentials);
            $io->success("Application {$this->credentials['username']} is successfuly registered.");
        } catch (ConflictHttpException $e) {
            $io->error("Application {$this->credentials['username']} is already registered.");
            $return = 1;
        } catch (BadRequestHttpException $e) {
            $io->error("Application must have credentials to register.");
            $return = 1;
        }

        return $return;
    }
}
