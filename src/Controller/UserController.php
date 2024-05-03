<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;

class UserController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $em)
    {}

    #[Route('/', name: 'welcome', methods: 'GET')]
    public function index(): JsonResponse
    {
        return $this->json([
            'message' => 'Welcome to next basket user service'
        ], 200);
    }

    #[Route('/users', name: 'create', methods: 'POST')]
    public function create(Request $request): JsonResponse
    {
        $parameters = json_decode($request->getContent(), true);
        $user = new User();
        $user->setEmail($parameters['email']);
        $user->setFirstName($parameters['firstName']);
        $user->setLastName($parameters['lastName']);

        $this->em->persist($user);
        $this->em->flush();

        return $this->json([
            'message' => 'User created successfully',
            'success' => true
        ], 201);
    }
}
