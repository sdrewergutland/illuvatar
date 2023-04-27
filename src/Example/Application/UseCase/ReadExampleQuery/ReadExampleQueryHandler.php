<?php

declare(strict_types=1);

namespace App\Example\Application\UseCase\ReadExampleQuery;

use App\Example\Domain\Example\Example;
use App\Example\Domain\Example\ExampleRepository;
use App\Shared\Application\Query\QueryHandler;

final readonly class ReadExampleQueryHandler implements QueryHandler
{
    public function __construct(
        private ExampleRepository $exampleRepository,
    ) {
    }

    public function __invoke(ReadExampleQuery $query): Example
    {
        return $this->exampleRepository->mustFindOneById($query->exampleId);
    }
}
