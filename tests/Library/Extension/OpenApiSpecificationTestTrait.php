<?php

declare(strict_types=1);

namespace App\Tests\Library\Extension;

use League\OpenAPIValidation\PSR7\Exception as ValidationException;
use League\OpenAPIValidation\PSR7\ValidatorBuilder;
use League\OpenAPIValidation\Schema\Exception as SchemaException;
use Nyholm\Psr7\Factory\Psr17Factory;
use PHPUnit\Framework\Assert;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Respect\Validation\Exceptions\ValidatorException;
use Symfony\Bridge\PsrHttpMessage\Factory\PsrHttpFactory;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

trait OpenApiSpecificationTestTrait
{
    abstract private static function getContainer(): ContainerInterface;

    // @todo: split request and response validation?
    public static function assertMatchesOpenApiSpecification(Request $request, Response $response): void
    {
        $specsFilePath = self::getContainer()->getParameter('app.shared.infrastructure.api_specifications_file_path');
        \assert(is_string($specsFilePath) && !empty($specsFilePath), 'OpenAPI specification file path is not a string or is empty');
        \assert(is_file($specsFilePath), 'OpenAPI specification file not found');
        \assert(is_readable($specsFilePath), 'OpenAPI specification file is not readable');

        $fileContent = file_get_contents($specsFilePath);
        \assert(is_string($fileContent) && !empty($fileContent), 'OpenAPI specification file content is not a string or is empty');

        $assertionErrorMessages = [];
        $validatorBuilder = (new ValidatorBuilder())->fromJson($fileContent);
        $requestValidator = $validatorBuilder->getRequestValidator();
        $matchedOperation = null;

        try {
            $request = self::openApiSpecsConvertToPsr17($request);
            \assert($request instanceof ServerRequestInterface, 'Request is not a ServerRequestInterface');

            $matchedOperation = $requestValidator->validate($request);
        } catch (ValidationException\ValidationFailed $exception) {
            Assert::fail($exception->getMessage());
        } catch (SchemaException\InvalidSchema $exception) {
            $previous = $exception->getPrevious();
            if ($previous instanceof ValidatorException) {
                $assertionErrorMessages[] = $previous->getFullMessage();
            }
            $assertionErrorMessages[] = $exception->getMessage();
            Assert::fail(join(PHP_EOL, $assertionErrorMessages));
        }

        $responseValidator = $validatorBuilder->getResponseValidator();

        try {
            $response = self::openApiSpecsConvertToPsr17($response);
            \assert($response instanceof ResponseInterface, 'Response is not a ResponseInterface');

            $responseValidator->validate($matchedOperation, $response);
        } catch (ValidationException\Validation\InvalidBody $exception) {
            $assertionErrorMessages[] = $exception->getMessage();
            if ($exception->getPrevious() instanceof SchemaException\SchemaMismatch) {
                $assertionErrorMessages[] = $exception->getPrevious()->getMessage();
                $assertionErrorMessages[] = join(' >> ', $exception->getPrevious()->dataBreadCrumb()?->buildChain() ?? []);
            }
        } catch (ValidationException\ValidationFailed $exception) {
            $assertionErrorMessages[] = $exception->getMessage();
        }

        if (!empty($assertionErrorMessages)) {
            Assert::fail(join(PHP_EOL, $assertionErrorMessages));
        }
    }

    // @todo: move this to a separate class
    private static function openApiSpecsConvertToPsr17(object $message): RequestInterface|ResponseInterface
    {
        if ($message instanceof ResponseInterface || $message instanceof ServerRequestInterface) {
            return $message;
        }

        $psr17Factory = new Psr17Factory();
        $psrHttpFactory = new PsrHttpFactory($psr17Factory, $psr17Factory, $psr17Factory, $psr17Factory);

        if ($message instanceof Response) {
            return $psrHttpFactory->createResponse($message);
        }

        if ($message instanceof Request) {
            return $psrHttpFactory->createRequest($message);
        }

        throw new \InvalidArgumentException(sprintf('Unsupported %s object received', get_class($message)));
    }
}
