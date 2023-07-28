<?php

namespace App\Controller\Api\v1;

use App\Entity\Product;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\IsCorrectType;
use App\Service\ProductScraper;
use App\Service\RandomGenerator;
use Doctrine\ORM\EntityManagerInterface;

//use OpenApi\Annotations as OA;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    #[Route('/test', name: 'test', methods: ['GET'])]
    public function test(ProductScraper $productScraper): JsonResponse
    {
        dd($productScraper->test(52));
    }
}
