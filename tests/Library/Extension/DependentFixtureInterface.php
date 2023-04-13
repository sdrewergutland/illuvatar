<?php

declare(strict_types=1);

namespace App\Tests\Library\Extension;

interface DependentFixtureInterface
{
    /**
     * @return array<string>
     */
    public function getDependencies(): array;
}
