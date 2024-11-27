<?php

namespace App\Controller;

use App\Form\CoursesCollectionFormType;
use App\Repository\CourseRepository;
use App\Repository\CourseTitleRepository;
use App\Repository\SemesterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AffectationController extends AbstractController
{
    #[Route('/affectation/{id}', name: 'app_affectation')]
    public function index(#[MapEntity] int $id, SemesterRepository $sRepository, CourseTitleRepository $ctRepository, CourseRepository $courseRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $semester = $sRepository->find(['id' => $id]);
        $courseTitles = $ctRepository->findBySemesterId($semester->getId());
        $courses = $courseRepository->findBySemesterId($semester->getId());

        $form = $this->createForm(CoursesCollectionFormType::class, ['courses' => $courses]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newCourses = $form->get('courses')->getData();

            foreach ($courses as $course) {
                if (!in_array($course, $newCourses)) {
                    $course->getAffectations()->clear();
                    $entityManager->persist($course);
                }
            }

            foreach ($newCourses as $course) {
                $entityManager->persist($course);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_affectation', ['id' => $id]);
        }

        return $this->render('affectation/index.html.twig', [
            'courseTitles' => $courseTitles,
            'form' => $form->createView(),
            'semester' => $semester,
        ]);
    }
}
