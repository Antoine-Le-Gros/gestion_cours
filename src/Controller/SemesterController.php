<?php

namespace App\Controller;

use App\Repository\SemesterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class SemesterController extends AbstractController
{
    #[Route('/semester', name: 'app_semester')]
    public function index(): Response
    {
        return $this->render('semester/index.html.twig', [
            'controller_name' => 'SemesterController',
        ]);
    }

    public function getSemestersByYear(Request $request, SemesterRepository $repository): JsonResponse
    {
        $yearId = $request->query->get('year');
        if (!$yearId) {
            return new JsonResponse(['error' => 'Year ID is required'], 400);
        }

        $semesters = $repository->findByYearId((int) $yearId);

        return $this->json($semesters);
    }
}
