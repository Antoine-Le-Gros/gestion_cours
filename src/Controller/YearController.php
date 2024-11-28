<?php

namespace App\Controller;

use App\Entity\Year;
use App\Form\YearType;
use App\Repository\AffectationRepository;
use App\Repository\CourseRepository;
use App\Repository\YearRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class YearController extends AbstractController
{
    #[Route('/year', name: 'app_year_index')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request, YearRepository $yearRepository): Response
    {
        return $this->render('year/index.html.twig', [
            'years' => $yearRepository->findAll(),
        ]);
    }

    #[Route('/year/{id}', name: 'app_year_show', requirements: ['id' => '\d+'])]
    #[IsGranted('ROLE_ADMIN')]
    public function show(#[MapEntity(expr: 'repository.findById(id)')] Year $year, Request $request, EntityManagerInterface $entityManager, AffectationRepository $aRepository, CourseRepository $cRepository): Response
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

        if ($this->isCsrfTokenValid('delete'.$year->getId(), $request->get('_token'))) {
            $entityManager->remove($year);
            $entityManager->flush();

            return $this->redirectToRoute('app_year_index', [], 301);
        }

        return $this->render('year/show.html.twig', [
            'year' => $year,
            'form' => $form,
        ]);
    }
}
