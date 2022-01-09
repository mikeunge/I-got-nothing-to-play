<?php

namespace App\Controller;

use App\Entity\Games;
use App\Entity\Developers;
use App\Entity\Genres;
use App\Entity\Plattforms;
use App\Form\GamesType;
use App\Repository\GamesRepository;
use App\Repository\DevelopersRepository;
use App\Repository\GenresRepository;
use App\Repository\PlattformsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Service\FileUploader;
use App\Form\FileUploadType;

#[Route('/games')]
class GamesController extends AbstractController
{
	function array_search_partial($arr, $keyword)
	{
		foreach($arr as $index => $string) {
			if (strpos($string, $keyword) !== FALSE)
				return $index;
		}
	}

    #[Route('/import', name: 'games_import', methods: ['GET', 'POST'])]
	public function upload(Request $request, FileUploader $fileUploader, GamesRepository $gamesRepository, DevelopersRepository $developersRepository, GenresRepository $genresRepository, PlattformsRepository $plattformsRepository, EntityManagerInterface $entityManager): Response
	{
		// how the csv should be formatted
		$csvFormat = array('Title', 'Description', 'Developer', 'Genre', 'Plattform', 'Image');
		$error = null;
		// create and handle form
		$form = $this->createForm(FileUploadType::class);
		$form->handleRequest($request);
		if ($form->isSubmitted() && $form->isValid()) {
			// get file from upload
			$file = $form['upload_file']->getData();
			if ($file) {
				$fileName = $fileUploader->upload($file);
				if ($fileName !== null) {
					// get the filepath 
					$directory = $fileUploader->getTargetDirectory();
					$filePath = $directory.'/'.$fileName;
					$row = 1;
					// open the file for read and assign to handle
					if (($handle = fopen($filePath, "r")) !== FALSE) {
						while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
							$row++;
							$length = count($data);
							// check if the csv is correctly formatted
							for ($i=0; $i < $length; $i++) {
								if ($row == 2) {
									if ($data[$i] != $csvFormat[$i]) {
										$error = 'CSV is not correctly formatted, please check your heading names, it should look like this: (Title;Description;Developer;Genre;Plattform;Image).';
										break;
									}
									continue;
								}
							}
							
							// catch errors
							if ($error) {
								break;
							} elseif ($row == 2) {
								continue;
							}

							$games = $gamesRepository->findByTitle($data[0]);
							if (count($games) > 0) {	// need to do some updating
								$games = $games[0];
								if ($games->getDescription() != $data[1]){
									$games->setDescription($data[1]);
								}
								if (!in_array($data[2], array($games->getDeveloper()))) {
									$developer = $developersRepository->findByName($data[2]);
									if (count($developer) == 0) {
										$developer = new Developer();
										$developer->setName($data[2]);
										$entityManager->persist($developer);
									}
									$games->setDeveloper($developer);
								}
								if (!in_array($data[3], array($games->getGenre()))) {
									$genres = $genresRepository->findByName($data[3]);
									if (count($genres) == 0) {
										$genres = new Genre();
										$genres->setName($data[3]);
										$entityManager->persist($genres);
									}
									foreach ($genres as $genre) {
										$games->addGenre($genre);
									}
								}
								if (!in_array($data[4], array($games->getPlattform()))) {
									$plattforms = $plattformsRepository->findByName($data[4]);
									if (count($plattforms) == 0) {
										$plattforms = new Plattforms();
										$plattforms->setName($data[4]);
										$entityManager->persist($plattforms);
									}
									foreach ($plattforms as $plattform) {
										$games->addPlattform($plattform);
									}
								}
								if ($games->getImage() != $data[5]) {
									$games->setImage($data[5]);
								}
								$game->setRating(1);
								$entityManager->persist($games);
								$entityManager->flush();
							} else {	// create a new game
								// check if needed entities exist
								$developer = $developersRepository->findByName($data[2]);
								$genre = $genresRepository->findByName($data[3]);
								$plattform = $plattformsRepository->findByName($data[4]);
								// create new entity or use existing
								if (count($developer) == 0) {
									$developer = new Developers();
									$developer->setName($data[2]);
									$entityManager->persist($developer);
								} else {
									$developer = $developer[0];
								}
								if (count($genre) == 0) {
									$genre = new Genres();
									$genre->setName($data[3]);
									$entityManager->persist($genre);
								} else {
									$genre = $genre[0];
								}
								if (count($plattform) == 0) {
									$plattform = new Plattforms();
									$plattform->setName($data[3]);
									$entityManager->persist($plattform);
								} else {
									$plattform = $plattform[0];
								}
								$game = new Games();
								$game->setTitle($data[0]);
								$game->setDescription($data[1]);
								$game->setDeveloper($developer);
								$game->addGenre($genre);
								$game->addPlattform($plattform);
								$game->setImage($data[5]);
								$game->setRating(1);
							
								// persist and flush the data
								$entityManager->persist($game);
								$entityManager->flush();
							}
						}
						fclose($handle);
					}
				}
			}
		}

		if ($error) {
			return $this->render('games/import.html.twig', [
				'form' => $form->createView(),
				'error' => $error,
			]);
		}
		return $this->redirectToRoute('games_index');
	}

    #[Route('/', name: 'games_index', methods: ['GET'])]
    public function index(Request $request, GamesRepository $gamesRepository): Response
    {
		$availableFilter = array('plattform', 'developer', 'genre');
		$search = $request->query->get('search');
		$filter = $request->query->get('filter');
		$error = null;

		if ($search != '') {
			if ($filter != '') {
				// check for what type of filter to use
				$filterArr = explode(':', $filter);
				if (!in_array($filterArr[0], $availableFilter)) {
					$error = 'Filter is wrong, please try again.';
				} else {
					$filterType = $filterArr[0];
					$filterString = $filterArr[1];
				}
			}
			$games = $gamesRepository->findLikeTitle($search);
			
			return $this->render('games/index.html.twig', [
				'games' => $games,
				'searchString' => $search,
				'filterOptions' => $availableFilter,
				'selectedFilter' => $filter,
				'error' => $error,
			]);
		} else {
			return $this->render('games/index.html.twig', [
				'games' => $gamesRepository->findAll(),
				'searchString' => null,
				'filterOptions' => $availableFilter,
				'selectedFilter' => null,
				'error' => $error,
			]);
		}
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
