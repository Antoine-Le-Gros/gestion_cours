<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ReactController extends AbstractController
{
    #[Route('/react', name: 'app_react')]
    #[Route('/me', name: 'app_me')]
    #[Route('/history/:yearId/:semesterId', name: 'app_show_semester')]
    #[Route('/discover', name: 'app_discover')]
    public function index(): Response
    {
        return $this->render('react/index.html.twig', [
            'controller_name' => 'ReactController',
        ]);
    }
}
