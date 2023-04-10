<?php

declare(strict_types=1);

namespace App\Tests\FunctionalSuite\Example\Infrastructure\Sql;

use App\Example\Domain\Example\Example;
use App\Example\Domain\Example\ExampleName;
use App\Example\Domain\Example\ExampleNotFoundException;
use App\Example\Domain\Example\ExampleRepository;
use App\Example\Infrastructure\Sql\ORMExampleRepository as Subject;
use App\Tests\Library\Extension\EntityManagerAwareTestTrait;
use App\Tests\Library\FunctionalTestCase;
use Symfony\Component\Uid\Uuid;

/**
 * @coversDefaultClass \App\Example\Infrastructure\Sql\ORMExampleRepository
 *
 * @internal
 */
class OrmExampleRepositoryTest extends FunctionalTestCase
{
    use EntityManagerAwareTestTrait;

    private Subject $subject;

    public function setUp(): void
    {
        $this->subject = self::getContainer()->get(Subject::class);
    }

    /**
     * @test
     */
    public function itIsRightlyRegistered(): void
    {
        $this->assertSame(
            $this->subject,
            self::getContainer()->get(ExampleRepository::class)
        );
    }

    /**
     * @test
     */
    public function itSavesAndFindsEntities(): void
    {
        $entity = new Example(
            id: Uuid::v4(),
            name: ExampleName::fromString('Example name')
        );

        $this->subject->save($entity);

        self::clearEntityManager();

        $this->assertEquals(
            $entity,
            $this->subject->mustFindOneById($entity->getId())
        );
    }

    /**
     * @test
     */
    public function itThrowsExceptionOnNonExistingEntity(): void
    {
        $this->expectException(ExampleNotFoundException::class);
        $this->subject->mustFindOneById(Uuid::v4());
    }
}
