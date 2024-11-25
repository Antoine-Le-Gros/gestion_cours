<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReactController extends AbstractController
{
    #[Route('/react', name: 'app_react')]
    #[Route('/me', name: 'app_me')]
    #[Route('/discover', name: 'app_discover')]
    #[Route('/history/year', name: 'app_history_year')]
    #[Route('/history/teacher', name: 'app_history_teacher')]
    #[Route('/history/teacher/{id}', name: 'app_history_teacher')]
    public function index(): Response
    {
        return $this->render('react/index.html.twig', [
            'controller_name' => 'ReactController',
        ]);
    }
}
