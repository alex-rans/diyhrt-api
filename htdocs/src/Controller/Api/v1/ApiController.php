<?php

namespace App\Controller\Api\v1;

use App\Entity\Product;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\IsCorrectType;
use App\Service\RandomGenerator;
use Doctrine\ORM\EntityManagerInterface;

//use OpenApi\Annotations as OA;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{


}
