<?php

namespace App\Controller;

use App\Entity\Year;
use App\Form\YearType;
use App\Repository\AffectationRepository;
use App\Repository\CourseRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class YearController extends AbstractController
{
    #[Route('/year/{id}', name: 'app_year_show', requirements: ['id' => '\d+'])]
    public function index(#[MapEntity(expr: 'repository.findById(id)')] Year $year, Request $request, EntityManagerInterface $entityManager, AffectationRepository $aRepository, CourseRepository $cRepository): Response
    {
        foreach ($year->getSemesters() as $semester) {
            $affectations = $aRepository->getAllAffectationGroupsTakenBySemester($semester->getId());
            $groups = $cRepository->getNumberOfGroupsPerSemester($semester->getId());
            $semester->setCompletion($affectations / $groups);
        }
        $form = $this->createForm(YearType::class, $year);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($year);
            $entityManager->flush();

            return $this->redirectToRoute('app_year_show', ['id' => $year->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('year/show.html.twig', [
            'year' => $year,
            'form' => $form,
        ]);
    }
}
