<?php

namespace App\Controller;

use App\Form\UploadType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UploadController extends AbstractController
{
    #[Route('/upload', name: 'app_upload')]
    public function index(Request $request): Response
    {
        $form = $this->createForm(UploadType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $excelFile = $form->get('excelFile')->getData();

            if ($excelFile) {
                $originalFilename = pathinfo($excelFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename.'-'.uniqid().'.'.$excelFile->guessExtension();

                try {
                    $excelFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    return new Response("Erreur lors de l'upload du fichier.");
                }
                /* Changer la route vers celle de la création d'une année
                return $this->redirectToRoute('app_route', ['filename' => $newFilename]);
                */
            }
        }

        return $this->render('upload/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
