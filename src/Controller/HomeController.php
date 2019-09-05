<?php

namespace App\Controller;

use App\Entity\Conference;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        $repository = $this->getDoctrine()->getRepository(Conference::class);
        $conference = $repository->findAll();

        return $this->render('home/index.html.twig', [
            'conference' => $conference
        ]);
    }
}
