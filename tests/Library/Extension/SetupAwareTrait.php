<?php

namespace App\Tests\Library\Extension;

trait SetupAwareTrait
{
    abstract protected static function getContainer(): \Symfony\Component\DependencyInjection\ContainerInterface;

    protected function testCaseSetup(): TestCaseSetup
    {
        return TestCaseSetup::create(self::getContainer());
    }
}
