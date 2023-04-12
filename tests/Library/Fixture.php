<?php

declare(strict_types=1);

namespace App\Tests\Library;

use Doctrine\Bundle\FixturesBundle as DoctrineFixturesBundle;

abstract class Fixture extends DoctrineFixturesBundle\Fixture
{
    abstract public function getDefaultReference(): mixed;
}
