<?php

namespace App\Example\Application\UseCase\CreateExample;

use App\Example\Domain\Example\Example;
use App\Example\Domain\Example\ExampleId;
use App\Example\Domain\Example\ExampleRepository;
use App\Shared\Application\Command\CommandHandler;
use App\Shared\Domain\Event\DomainEventBusInterface;

final readonly class CreateExampleCommandHandler implements CommandHandler
{
    public function __construct(
        private ExampleRepository $exampleRepository,
        private DomainEventBusInterface $domainEventBus,
    ) {
    }

    public function __invoke(CreateExampleCommand $command): ExampleId
    {
        $example = Example::create(
            id: ExampleId::random(),
            name: $command->getName(),
        );

        $this->exampleRepository->save($example);

        $this->domainEventBus->publish($example->releaseDomainEvents());

        return $example->getId();
    }
}
