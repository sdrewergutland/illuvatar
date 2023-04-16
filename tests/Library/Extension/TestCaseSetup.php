<?php

declare(strict_types=1);

namespace App\Tests\Library\Extension;

use App\Tests\Library\Extension\Fixture\Fixture;
use Symfony\Component\DependencyInjection\ContainerInterface;

final class TestCaseSetup
{
    private ContainerInterface $container;
    private bool $isRequiresInitializedDatabase = true;

    /**
     * @var Fixture[]
     */
    private array $fixturesToLoad = [];

    private function __construct()
    {
        // not allowed
    }

    public static function create(ContainerInterface $container): self
    {
        $self = new self();
        $self->container = $container;

        return $self;
    }

    public function mustLoadFixture(string $fixtureClassName): self
    {
        assert(class_exists($fixtureClassName), "The fixture class '{$fixtureClassName}' does not exist.");
        $fixtureInstance = $this->container->get($fixtureClassName);

        assert($fixtureInstance instanceof Fixture, "The fixture class '{$fixtureClassName}' does not implement the interface Fixture.");
        $this->fixturesToLoad[get_class($fixtureInstance)] = $fixtureInstance;

        return $this;
    }

    /**
     * @return Fixture[]
     */
    public function getFixturesToLoad(): array
    {
        return $this->fixturesToLoad;
    }

    public function isRequiresInitializedDatabase(): bool
    {
        return $this->isRequiresInitializedDatabase;
    }

    /**
     * @throws \Throwable
     */
    public function run(): self
    {
        $setupRunner = $this->container->get(SetupRunner::class);
        assert($setupRunner instanceof SetupRunner, 'The setup runner must implement the interface SetupRunner.');
        $setupRunner->setup($this);

        return $this;
    }
}
