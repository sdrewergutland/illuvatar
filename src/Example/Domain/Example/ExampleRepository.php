<?php

declare(strict_types=1);

namespace App\Example\Domain\Example;

interface ExampleRepository
{
    public function save(Example $example): void;

    public function mustFindOneById(ExampleId $id): Example;

    public function findOneById(ExampleId $id): ?Example;
}
