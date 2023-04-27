<?php

declare(strict_types=1);

namespace App\Tests\FunctionalSuite\Example\Infrastructure\Sql;

use App\Example\Domain\Example\Example;
use App\Example\Domain\Example\ExampleId;
use App\Example\Domain\Example\ExampleName;
use App\Example\Domain\Example\ExampleNotFoundException;
use App\Example\Domain\Example\ExampleRepository;
use App\Example\Infrastructure\Sql\ORMExampleRepository as Subject;
use App\Tests\Library\Extension\EntityManagerAwareTestTrait;
use App\Tests\Library\Extension\SetupAwareTrait;
use App\Tests\Library\FunctionalTestCase;
use App\Tests\Resources\Fixture\Example\AnotherExampleFixture;
use App\Tests\Resources\Fixture\Example\ExampleFixture;

/**
 * @coversDefaultClass \App\Example\Infrastructure\Sql\ORMExampleRepository
 *
 * @internal
 */
class OrmExampleRepositoryTest extends FunctionalTestCase
{
    use EntityManagerAwareTestTrait;
    use SetupAwareTrait;

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
    public function itFindsEntities(): void
    {
        $this->testCaseSetup()
            ->mustLoadFixture(ExampleFixture::class)
            ->mustLoadFixture(AnotherExampleFixture::class)
            ->run()
        ;

        $this->assertNotNull(
            $this->subject->findOneById(ExampleFixture::getDefaultReference()->getId())
        );
    }

    /**
     * @test
     */
    public function itSavesAndFindsEntities(): void
    {
        $entity = Example::create(
            id: ExampleId::random(),
            name: ExampleName::fromString('Example name')
        );

        $this->subject->save($entity);

        self::clearEntityManager();

        $entity->releaseDomainEvents();

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
        $this->subject->mustFindOneById(ExampleId::random());
    }
}
