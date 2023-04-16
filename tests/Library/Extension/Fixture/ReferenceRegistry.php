<?php

declare(strict_types=1);

namespace App\Tests\Library\Extension\Fixture;

class ReferenceRegistry
{
    /** @var array<string, mixed> */
    private array $references = [];

    private static ?self $instance = null;

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    private function __construct()
    {
        // disallowed
    }

    public function reset(): self
    {
        $this->references = [];

        return $this;
    }

    public function addReference(string $key, mixed $reference): void
    {
        $this->references[$key] = $reference;
    }

    public function getReference(string $key): mixed
    {
        $reference = $this->references[$key] ?? null;

        if (!$reference) {
            throw new \OutOfBoundsException("The reference with the key '{$key}' does not exist. You probably forgot to load the fixture first.");
        }

        return $reference;
    }
}
