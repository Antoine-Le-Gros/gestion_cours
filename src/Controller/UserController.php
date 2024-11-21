<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    #[Route('/home_admin', name: 'app_home_admin')]
    #[IsGranted('ROLE_ADMIN')]
    public function home_admin(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/user', name: 'app_user_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request, UserRepository $userRepository): Response
    {
        $query = $request->query->get('query', '');
        $isActive = $request->query->get('isActive', null);

        $isActive = null !== $isActive && '' !== $isActive ? (bool) $isActive : null;

        $users = $userRepository->findBySearchQuery($query, $isActive);

        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('user/new', name: 'app_user_new', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword($this->passwordHasher->hashPassword($user, 'test'));
            $user->setIsActive(true);
            if ($form->get('user_isAdministration')->getData()) {
                $userRoles = $user->getRoles();
                if (!in_array(User::ADMINISTRATION, $userRoles, true)) {
                    $userRoles[] = User::ADMINISTRATION;
                }
                $userRoles = array_values(array_unique($userRoles));
                $user->setRoles($userRoles);
            }
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('user/{id}/edit', name: 'app_user_edit', methods: ['GET', 'POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($form->get('isAdministration')->getData()) {
                $userRoles = $user->getRoles();
                if (!in_array(User::ADMINISTRATION, $userRoles, true)) {
                    $userRoles[] = User::ADMINISTRATION;
                }
                $userRoles = array_values(array_unique($userRoles));
                $user->setRoles($userRoles);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('user/{id}/deactivate', name: 'app_user_deactivate', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function deactivate(User $user, EntityManagerInterface $entityManager): RedirectResponse
    {
        $user->setIsActive(false);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('user/{id}/activate', name: 'app_user_activate', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function activate(User $user, EntityManagerInterface $entityManager): RedirectResponse
    {
        $user->setIsActive(true);
        $entityManager->flush();

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
