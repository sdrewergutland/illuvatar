<?php

namespace App\Example\Infrastructure\WebApi\Controller;

use App\Example\Application\ExampleApplicationInterface;
use App\Example\Application\UseCase\CreateExample\CreateExampleCommand;
use App\Example\Domain\Example\ExampleName;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class CreateExample extends AbstractController
{
    #[Route('/example/example',
        name: 'example_example_create',
        methods: ['POST']
    )]
    public function __invoke(ExampleApplicationInterface $exampleApplication, Request $request): Response
    {
        // @todo: Replace with a DTO and symfony validation
        $data = json_decode($request->getContent(), true);
        assert(is_array($data) && isset($data['name']) && is_string($data['name']));

        return $this->json(
            $exampleApplication->createExample(
                new CreateExampleCommand(
                    ExampleName::fromString($data['name']),
                )
            )
        );
    }
}
