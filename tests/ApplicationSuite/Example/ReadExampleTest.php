<?php

declare(strict_types=1);

namespace App\Tests\ApplicationSuite\Example;

use App\Tests\Library\ApplicationTestCase;
use App\Tests\Library\Extension\OpenApiSpecificationTestTrait;
use App\Tests\Resources\Fixture\Example\ExampleFixture;

/**
 * @internal
 *
 * @coversNothing
 */
final class ReadExampleTest extends ApplicationTestCase
{
    use OpenApiSpecificationTestTrait;

    /**
     * @test
     */
    public function itReadsExample(): void
    {
        $this->testCaseSetup()
            ->mustLoadFixture(ExampleFixture::class)
            ->run()
        ;

        $client = $this->createClient();
        $client->request(
            ...$this->createRequestBuilder()
                ->withUri('/example/example/{exampleId}')
                ->withUriParamInPath('exampleId', (string) ExampleFixture::getDefaultReference()->getId())
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
