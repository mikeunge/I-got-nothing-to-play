<?php

namespace App\Controller;

use App\Entity\Games;
use App\Form\GamesType;
use App\Repository\GamesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/games')]
class GamesController extends AbstractController
{
    #[Route('/', name: 'games_index', methods: ['GET'])]
    public function index(GamesRepository $gamesRepository): Response
    {
        return $this->render('games/index.html.twig', [
            'games' => $gamesRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'games_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $game = new Games();
        $form = $this->createForm(GamesType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($game);
            $entityManager->flush();

            return $this->redirectToRoute('games_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('games/new.html.twig', [
            'game' => $game,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'games_show', methods: ['GET'])]
    public function show(Games $game): Response
    {
        return $this->render('games/show.html.twig', [
            'game' => $game,
        ]);
    }

    #[Route('/{id}/edit', name: 'games_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Games $game, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GamesType::class, $game);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('games_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('games/edit.html.twig', [
            'game' => $game,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'games_delete', methods: ['POST'])]
    public function delete(Request $request, Games $game, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$game->getId(), $request->request->get('_token'))) {
            $entityManager->remove($game);
            $entityManager->flush();
        }

        return $this->redirectToRoute('games_index', [], Response::HTTP_SEE_OTHER);
    }
}
