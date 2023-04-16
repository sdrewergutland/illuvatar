<?php

declare(strict_types=1);

namespace App\Example\Application\UseCase\ReadExampleQuery;

use App\Shared\Application\Query\Query;
use Symfony\Component\Uid\Uuid;

final class ReadExampleQuery implements Query
{
    public function __construct(
        public readonly Uuid $exampleId,
    ) {
    }
}
