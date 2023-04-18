<?php

namespace App\Example\Application\UseCase\CreateExample;

use App\Example\Domain\Example\Example;
use App\Example\Domain\Example\ExampleName;
use App\Example\Domain\Example\ExampleRepository;
use App\Shared\Application\Command\CommandHandler;
use Symfony\Component\Uid\Uuid;

readonly class CreateExampleCommandHandler implements CommandHandler
{
    public function __construct(
        private ExampleRepository $exampleRepository,
    ) {
    }

    public function __invoke(CreateExampleCommand $command): void
    {
        $example = new Example(
            id: Uuid::v7(),
            name: ExampleName::fromString($command->getName()),
        );

        $this->exampleRepository->save($example);
    }
}
