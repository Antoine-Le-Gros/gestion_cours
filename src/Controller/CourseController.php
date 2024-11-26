<?php

namespace App\Controller;

use App\Repository\CourseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class CourseController extends AbstractController
{
    #[Route('/course', name: 'app_course_index', methods: ['GET'])]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request, CourseRepository $courseRepository): Response
    {
        $query = $request->query->get('query', '');
        $type = $request->query->get('type', '');

        $courses = $courseRepository->findBySearchQuery($query, $type);

        return $this->render('course/index.html.twig', [
            'courses' => $courses,
        ]);
    }
}
