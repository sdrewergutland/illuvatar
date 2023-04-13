<?php

declare(strict_types=1);

namespace App\Tests\Library\Extension;

interface ReferenceRegistryAwareInterface
{
    public static function getReferenceRegistry(): ReferenceRegistry;

    public static function addReference(string $key, mixed $reference): void;

    public static function getReference(string $key): mixed;
}
