<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Entity\Vote;
use App\Form\ConferenceFormType;
use App\Form\VoteFormType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
