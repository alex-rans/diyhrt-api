<?php

namespace App\Controller\Api\v1;

use App\Entity\User;
use App\Service\RandomGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use OpenApi\Attributes as OA;


class UserController extends AbstractController
{
    #[Route('/api/v1/user', name: 'v1_getUsers', methods: ['GET'])]
    public function getUsers(EntityManagerInterface $entityManager): JsonResponse
    {
        $users = $entityManager->createQueryBuilder()
            ->select('u.id', 'u.email')
            ->from('App\Entity\User', 'u');
        return $this->json($users->getQuery()->getResult());
    }

    //get single user
    #[Route('/api/v1/user/{email}', name: 'v1_getUser', methods: ['GET'])]
    public function getUsesr(EntityManagerInterface $entityManager, $email): JsonResponse
    {
        $user = $entityManager->createQueryBuilder()
            ->select('u.id', 'u.email')
            ->from('App\Entity\User', 'u')
            ->where('u.email = :email')
            ->setParameter('email', $email);
        return $this->json($user->getQuery()->getResult());
    }

    //insert users
    #[Route('/api/v1/user', name: 'v1_insertUsers', methods: ['POST'])]
    public function insertUsers(
        EntityManagerInterface $entityManager,
        RandomGenerator        $randomGenerator,
        Request                $request): JsonResponse
    {
        $email = $request->get('email');
        $token = $randomGenerator->generateRandomString(64);

        $user = new User();
        $user->setEmail($email);
        $user->setApiToken($token);

        $entityManager->persist($user);
        $entityManager->flush($user);
        return $this->json($user);
    }

    //delete users
    #[OA\G]
    #[Route('/api/v1/user/{email}', name: 'v1_deleteUsers', methods: ['DELETE'])]
    public function deleteUsers(EntityManagerInterface $entityManager, $email): JsonResponse|Response
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(["email" => $email]);


        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return new Response('Error 400: Not a valid email');
        }
        if (!$user) {
            return new Response('Error 404: User not found', '404');
        }

        $entityManager->remove($user);
        $entityManager->flush();
        return $this->json('User deleted');
    }
}
