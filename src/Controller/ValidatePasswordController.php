<?php

// src/Controller/ValidatePasswordController.php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ValidatePasswordController extends AbstractController
{
    public function __invoke(Request $request, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher, int $id): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = $userRepository->find($id);

        if (!$user || !isset($data['password'])) {
            return new JsonResponse(['message' => 'Utilisateur ou mot de passe non trouvé.'], 400);
        }

        $isPasswordValid = $passwordHasher->isPasswordValid($user, $data['password']);

        if ($isPasswordValid) {
            return new JsonResponse(['valid' => true], 200);
        }

        return new JsonResponse(['valid' => false], 401);
    }
}
