<?php

declare(strict_types=1);

namespace App\Tests\ApplicationSuite\Example;

use App\Tests\Library\ApplicationTestCase;
use App\Tests\Library\Extension\OpenApiSpecificationTestTrait;

/**
 * @internal
 *
 * @coversNothing
 */
final class CreateExampleTest extends ApplicationTestCase
{
    use OpenApiSpecificationTestTrait;

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

        $this->assertMatchesOpenApiSpecification(
            request: $client->getRequest(),
            response: $client->getResponse(),
        );
    }
}
