<?php

namespace App\Tests\Library;

use App\Tests\Library\Extension\EntityManagerAwareTestTrait;
use App\Tests\Library\Extension\Request\RequestBuilder;
use App\Tests\Library\Extension\SetupAwareTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 *
 * @coversNothing
 */
class ApplicationTestCase extends WebTestCase
{
    use EntityManagerAwareTestTrait;
    use SetupAwareTrait;

    /**
     * @param array<string, string> $options
     * @param array<string, string> $server
     */
    protected static function createClient(array $options = [], array $server = []): KernelBrowser
    {
        self::ensureKernelShutdown();

        return parent::createClient($options, $server);
    }

    public function createRequestBuilder(): RequestBuilder
    {
        $builder = self::getContainer()->get(RequestBuilder::class);
        assert($builder instanceof RequestBuilder, 'RequestBuilder is not available');

        return $builder;
    }
}
