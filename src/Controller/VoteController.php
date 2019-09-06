<?php

namespace App\Controller;

use App\Entity\Vote;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class VoteController extends AbstractController
{
    /**
     * @Route("/vote", name="app_vote")
     */
    public function getVote()
    {
        $em = $this->getDoctrine()->getRepository(Vote::class);
        $vote = $em->findAll();

        return $this->render('vote/index.html.twig', [
            'vote' => $vote
        ]);
    }
}
