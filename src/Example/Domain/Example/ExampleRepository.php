<?php

declare(strict_types=1);

namespace App\Example\Domain\Example;

use Symfony\Component\Uid\Uuid;

interface ExampleRepository
{
    public function save(Example $example): void;

    public function mustFindOneById(Uuid $id): Example;
}
