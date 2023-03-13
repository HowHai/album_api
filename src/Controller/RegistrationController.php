<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\User;
use App\Repository\UserRepository;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function index(Request $request, SerializerInterface $serializer, UserPasswordHasherInterface $hasher, UserRepository $repository): JsonResponse
    {
        $user_data = json_decode($request->getContent());
        $user = new User();
        $hashed_password = $hasher->hashPassword(
            $user,
            $user_data->password
        );
        $user->setPassword($hashed_password);
        $user->setUsername($user_data->username);

        # TODO: add validations

        $repository->save($user, true);

        return $this->json(
            ['data' => $user],
        );
    }
}
