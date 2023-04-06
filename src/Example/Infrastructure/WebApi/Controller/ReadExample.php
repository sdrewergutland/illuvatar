<?php

namespace App\Example\Infrastructure\WebApi\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ReadExample extends AbstractController
{

    #[Route('/example/example', name: 'example_example_read', methods: ['GET'])]
    public function __invoke()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/CreateExampleController.php',
        ]);
    }
}
