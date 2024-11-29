<?php

namespace App\Controller;

use App\Entity\Affectation;
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
        $courseErrors = [];
        $affectationErrors = [];

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
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
            } else {
                foreach ($form->getErrors(true) as $error) {
                    $cause = $error->getCause();
                    if ($cause->getInvalidValue() instanceof Affectation) {
                        $affectationErrors[] = $cause->getInvalidValue()->getId();
                        $courseErrors[] = $cause->getInvalidValue()->getCourse()->getId();
                    } else {
                        $courseErrors[] = $cause->getInvalidValue()->getOwner()->getId();
                    }
                }
            }
        }

        $response = new Response(null, $form->isSubmitted() ? 422 : 200);

        return $this->render('affectation/index.html.twig', [
            'courseTitles' => $courseTitles,
            'form' => $form->createView(),
            'semester' => $semester,
            'courseErrors' => $courseErrors,
            'affectationsErrors' => $affectationErrors,
        ], $response);
    }
}
