<?php

namespace App\Example\Application;

use App\Example\Application\UseCase\ReadExampleQuery\ReadExampleQuery;
use App\Example\Domain\Example\Example;

interface ExampleApplicationInterface
{
    public function readExample(ReadExampleQuery $query): Example;
}
