<?php

declare(strict_types=1);

namespace App\Tests\Library\Extension\Fixture;

use Doctrine\DBAL\Connection;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class FixtureLoader
{
    /**
     * @var array<Fixture>
     */
    private array $fixtures = [];

    public function __construct(
        private readonly ContainerInterface $container,
        private readonly Connection $connection,
    ) {
    }

    /**
     * @param array<Fixture> $fixtures
     */
    public function load(array $fixtures): void
    {
        $this->fixtures = [];

        if (empty($fixtures)) {
            return;
        }

        foreach ($fixtures as $fixture) {
            \assert($fixture instanceof Fixture);

            $this->addFixture(
                $fixture,
                $this->container,
            );
        }
        $this->connection->beginTransaction();
        foreach ($this->fixtures as $fixture) {
            $fixture->load();
        }
        $this->connection->commit();
    }

    private function addFixture(
        Fixture $fixture,
        ContainerInterface $container
    ): void {
        if ($fixture instanceof ContainerAwareInterface) {
            $fixture->setContainer($container);
        }

        if ($fixture instanceof DependentFixtureInterface) {
            foreach ($fixture->getDependencies() as $_dependentFixtureClass) {
                $_dependentFixture = $container->get($_dependentFixtureClass);
                \assert($_dependentFixture instanceof Fixture);
                $this->addFixture(
                    $_dependentFixture,
                    $container,
                );
            }
        }

        $this->fixtures[get_class($fixture)] = $fixture;
    }
}
