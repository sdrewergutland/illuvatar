<?php

namespace App\Example\Infrastructure\WebApi\Controller;

use App\Example\Application\ExampleApplicationInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReadExample extends AbstractController
{
    #[Route('/example/example', name: 'example_example_read', methods: ['GET'])]
    public function __invoke(ExampleApplicationInterface $exampleApplication): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path'    => 'src/Controller/CreateExampleController.php',
        ]);
    }
}
