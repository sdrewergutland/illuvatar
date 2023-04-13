<?php

declare(strict_types=1);

namespace App\Tests\Library\Extension;

trait ReferenceRegistryAwareTrait
{
    /**
     * @after
     */
    public function clearRegistry(): void
    {
        self::getReferenceRegistry()->reset();
    }

    public static function getReferenceRegistry(): ReferenceRegistry
    {
        return ReferenceRegistry::getInstance();
    }

    public static function addReference(string $key, mixed $reference): void
    {
        self::getReferenceRegistry()->addReference($key, $reference);
    }

    public static function getReference(string $key): mixed
    {
        return self::getReferenceRegistry()->getReference($key);
    }
}
