<?php

declare(strict_types=1);

namespace App\Tests\Resources\Fixture\Example;

use App\Example\Domain\Example\Example as SubjectEntity;
use App\Example\Domain\Example\ExampleName;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Uid\UuidV4;

final class ExampleFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $subjectEntity = new SubjectEntity(
            UuidV4::v4(),
            new ExampleName(__CLASS__)
        );

        $manager->persist($subjectEntity);
        $manager->flush();
    }
}
