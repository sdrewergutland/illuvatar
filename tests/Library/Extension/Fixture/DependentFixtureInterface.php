<?php

declare(strict_types=1);

namespace App\Tests\Library\Extension\Fixture;

interface DependentFixtureInterface
{
    /**
     * @return array<string>
     */
    public function getDependencies(): array;
}
