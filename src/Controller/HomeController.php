<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController {

	#[Route('/', name: 'homepage')]
	public function index(): Response {
		return $this->render('homepage/homepage.html.twig');
	}
}

?>