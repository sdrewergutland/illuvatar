<?php

declare(strict_types=1);

namespace App\Tests\Library\Extension\Fixture;

abstract class Fixture implements ReferenceRegistryAwareInterface
{
    use ReferenceRegistryAwareTrait;

    abstract public static function getDefaultReference(): mixed;

    abstract public function load(): void;
}
