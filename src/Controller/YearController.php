<?php

namespace App\Controller;

use App\Entity\Year;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class YearController extends AbstractController
{
    #[Route('/year/{id}', name: 'app_year_show', requirements: ['id' => '\d+'])]
    public function index(#[MapEntity(expr: 'repository.findById(id)')] Year $year): Response
    {
        return $this->render('year/show.html.twig', ['year' => $year]);
    }
}
