<?php

declare(strict_types=1);

namespace App\Tests\ApplicationSuite\Example;

use App\Example\Domain\Example\Event\ExampleCreatedEvent;
use App\Example\Domain\Example\ExampleId;
use App\Example\Domain\Example\ExampleRepository;
use App\Tests\Library\ApplicationTestCase;
use App\Tests\Library\Extension\DomainEventBusAwareTestTrait;
use App\Tests\Library\Extension\OpenApiSpecificationTestTrait;

/**
 * @internal
 *
 * @coversNothing
 */
final class CreateExampleTest extends ApplicationTestCase
{
    use OpenApiSpecificationTestTrait;
    use DomainEventBusAwareTestTrait;

    /**
     * @test
     */
    public function itCreatesExample(): void
    {
        $this->testCaseSetup()->run();

        $client = $this->createClient();
        $client->request(
            ...$this->createRequestBuilder()
                ->withUri('/example/example')
                ->asPost()
                ->withBodyAsJson([
                    'name' => 'example name' . time(),
                ])
                ->build()
        );

        $this->assertResponseIsSuccessful();
        $this->assertResponseHeaderSame('Content-Type', 'application/json');

        $this->assertOpenApiSpecificationMatches(
            request: $client->getRequest(),
            response: $client->getResponse(),
        );

        $id = json_decode((string) $client->getResponse()->getContent(), true);
        $this->assertIsString($id);
        self::getContainer()->get(ExampleRepository::class)->mustFindOneById(
            ExampleId::fromString($id),
        );

        $this->assertDomainEventBusReceivedEvent(
            expectedEventClass: ExampleCreatedEvent::class,
        );
    }
}
