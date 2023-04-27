<?php

declare(strict_types=1);

namespace App\Example\Application\UseCase\ReadExampleQuery;

use App\Example\Domain\Example\ExampleId;
use App\Shared\Application\Query\Query;

final readonly class ReadExampleQuery implements Query
{
    public function __construct(
        public ExampleId $exampleId,
    ) {
    }
}
