<?php

namespace App\Example\Infrastructure\WebApi\Controller;

use App\Example\Application\ExampleApplicationInterface;
use App\Example\Application\UseCase\ReadExampleQuery\ReadExampleQuery;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\Uuid;

class ReadExample extends AbstractController
{
    #[Route('/example/example/{exampleId}',
        name: 'example_example_read',
        requirements: [
            'exampleId' => '[a-z0-9-]{36}',
        ],
        methods: ['GET']
    )]
    public function __invoke(ExampleApplicationInterface $exampleApplication, string $exampleId): Response
    {
        return $this->json(
            $exampleApplication->readExample(
                new ReadExampleQuery(
                    Uuid::fromString($exampleId)
                )
            )
        );
    }
}
