<?php

declare(strict_types=1);

namespace App\Tests\Resources\Fixture\Example;

use App\Example\Domain\Example\Example as SubjectEntity;
use App\Example\Domain\Example\ExampleId;
use App\Example\Domain\Example\ExampleName;
use App\Tests\Library\Extension\Fixture\DependentFixtureInterface;
use App\Tests\Library\Extension\Fixture\Fixture;
use Doctrine\ORM\EntityManagerInterface;

final class AnotherExampleFixture extends Fixture implements DependentFixtureInterface
{
    public function __construct(
        private readonly EntityManagerInterface $manager
    ) {
    }

    public function load(): void
    {
        $subjectEntity = new SubjectEntity(
            ExampleId::random(),
            ExampleName::fromString(__CLASS__)
        );

        $this->manager->persist($subjectEntity);
        $this->manager->flush();

        self::addReference(__CLASS__, $subjectEntity);
    }

    public static function getDefaultReference(): SubjectEntity
    {
        $reference = self::getReference(__CLASS__);
        \assert($reference instanceof SubjectEntity);

        return $reference;
    }

    public function getDependencies(): array
    {
        return [
            ExampleFixture::class,
        ];
    }
}
