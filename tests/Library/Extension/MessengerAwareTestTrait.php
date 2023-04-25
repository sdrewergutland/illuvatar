<?php

namespace App\Tests\Library\Extension;

use App\Tests\Library\Kernel\CommandRunner;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

trait MessengerAwareTestTrait
{
    abstract public static function getContainer(): ContainerInterface;

    private ?string $messengerTestOutput;

    /**
     * @after
     */
    public function messengerClearMessengerStatus(): void
    {
        $this->messengerTestOutput = null;
    }

    public function messengerRunWorkerForOneMessage(KernelInterface $kernel, string $receiver): void
    {
        $commandRunner = new CommandRunner($kernel);
        $this->messengerTestOutput = $commandRunner->mustRunCommand(
            name: 'messenger:consume',
            arguments: ['receivers' => [$receiver], '--limit' => 1, '--time-limit' => 1],
            options: [
                'verbosity'   => OutputInterface::VERBOSITY_VERBOSE,
                'interactive' => false,
            ]
        );
    }

    public function messengerRunWorkerOnDefaultForOneMessage(KernelInterface $kernel): void
    {
        $this->messengerRunWorkerForOneMessage(
            $kernel,
            'shared_messenger_transport.default',
        );
    }
}
