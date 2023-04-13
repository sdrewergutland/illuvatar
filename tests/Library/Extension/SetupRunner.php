<?php

declare(strict_types=1);

namespace App\Tests\Library\Extension;

final class SetupRunner
{
    public function __construct(
        private readonly FixtureLoader $fixtureLoader,
        private readonly SqlDatabaseInitializer $databaseInitializer
    ) {
    }

    public function setup(TestCaseSetup $setup): self
    {
        ReferenceRegistry::getInstance()->reset();

        return $this
            ->initDatabase()
            ->loadFixtures($setup)
        ;
    }

    private function initDatabase(): self
    {
        $this->databaseInitializer->initDatabase();

        return $this;
    }

    private function loadFixtures(TestCaseSetup $setup): self
    {
        $this->fixtureLoader->load($setup->getFixturesToLoad());

        return $this;
    }
}
