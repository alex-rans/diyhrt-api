<?php

namespace App\Controller\Api\v1;

use App\Service\RandomGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function Index(): Response
    {
        return $this->render('index.html.twig');
    }
//    #[Route('/test', name: 'test', methods: ['GET'])]
//    public function test(RandomGenerator $randomGenerator): JsonResponse
//    {
//        dd($randomGenerator->generateRandomString());
//    }
}
