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
        $conferences = $repository->findAll();

        $averageNote = [];
        foreach ($conferences as $conference)
        {
            $values = [];
            $votes = $conference->getVotes();
            foreach ($votes as $vote)
            {
                $values[] = $vote->getValue();
            }
            if (!empty($values))
            {
                $average = array_sum($values)/count($values);
                $averageNote[$conference->getId()] = $average;
            }
            else
            {
                $averageNote[$conference->getId()] = "not yet rated";
            }
        }

        return $this->render('home/index.html.twig', [
            'conferences' => $conferences,
            'average' => $averageNote
        ]);
    }
}
