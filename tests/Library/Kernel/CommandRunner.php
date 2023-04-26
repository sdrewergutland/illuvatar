<?php

namespace App\Tests\Library\Kernel;

use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;

class CommandRunner
{
    public function __construct(
        private readonly KernelInterface $kernel
    ) {
    }

    /**
     * @param string[]             $inputs
     * @param array<string, mixed> $arguments
     * @param array<string, mixed> $options
     */
    public function mustRunCommand(string $name, array $inputs = [], array $arguments = [], array $options = []): string
    {
        $kernel = $this->kernel;
        $application = new Application($kernel);

        $command = $application->find($name);
        $commandTester = new CommandTester($command);
        $commandTester->setInputs($inputs);
        $commandTester->execute($arguments, $options);
        $commandTester->assertCommandIsSuccessful();

        return $commandTester->getDisplay(true);
    }
}
