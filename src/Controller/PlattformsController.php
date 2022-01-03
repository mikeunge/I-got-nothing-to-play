<?php

namespace App\Controller;

use App\Entity\Plattforms;
use App\Form\PlattformsType;
use App\Repository\PlattformsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/plattforms')]
class PlattformsController extends AbstractController
{
    #[Route('/', name: 'plattforms_index', methods: ['GET'])]
    public function index(PlattformsRepository $plattformsRepository): Response
    {
        return $this->render('plattforms/index.html.twig', [
            'plattforms' => $plattformsRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'plattforms_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $plattform = new Plattforms();
        $form = $this->createForm(PlattformsType::class, $plattform);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($plattform);
            $entityManager->flush();

            return $this->redirectToRoute('plattforms_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('plattforms/new.html.twig', [
            'plattform' => $plattform,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'plattforms_show', methods: ['GET'])]
    public function show(Plattforms $plattform): Response
    {
        return $this->render('plattforms/show.html.twig', [
            'plattform' => $plattform,
        ]);
    }

    #[Route('/{id}/edit', name: 'plattforms_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Plattforms $plattform, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PlattformsType::class, $plattform);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('plattforms_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('plattforms/edit.html.twig', [
            'plattform' => $plattform,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'plattforms_delete', methods: ['POST'])]
    public function delete(Request $request, Plattforms $plattform, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$plattform->getId(), $request->request->get('_token'))) {
            $entityManager->remove($plattform);
            $entityManager->flush();
        }

        return $this->redirectToRoute('plattforms_index', [], Response::HTTP_SEE_OTHER);
    }
}
