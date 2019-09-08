<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Entity\User;
use App\Entity\Vote;
use App\Form\ConferenceFormType;
use App\Form\VoteFormType;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ConferenceController extends AbstractController
{

    /**
     * @Route("/conference/new", name="app_conference_new")
     */
    public function addConference(Request $request)
    {
        $conference = new Conference();
        $form = $this->createform(ConferenceFormType::class, $conference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $conference->setUser($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $em->persist($conference);
            $em->flush();
            $this->addFlash('success', 'Your conference proposal has been created.');
            return $this->redirectToRoute('home');
        }

        return $this->render('conference/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }



    /**
     * @Route("/conference/{id}", name="app_conference_id")
     * @ParamConverter("conference", class="App\Entity\Conference")
     */
    public function getOneConference(Conference $conference, Request $request, EntityManagerInterface $em)
    {

        if(is_null($conference))
        {
            throw new NotFoundHttpException();
        }

        $vote = new Vote();
        $form = $this->createForm(VoteFormType::class,$vote);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $vote->setUser($this->getUser());

            $vote->setConference($conference);
            $conference->addVote($vote);
            $em->persist($vote);
            $em->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('conference/conference_id.html.twig',[
            'conference' => $conference,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/conference/{id}/remove", name="app_conference_remove")
     * @ParamConverter("conference", class="App\Entity\Conference")
     */
    public function removeOneConference(Conference $conference)
    {
        if(is_null($conference))
        {
            throw new NotFoundHttpException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($conference);
        $em->flush();
        $this->addFlash('success', 'Your conference proposal as been deleted.');
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/conference/{id}/edit", name="app_conference_edit")
     * @ParamConverter("conference", class="App\Entity\Conference")
     */
    public function editConference(Conference $conference, Request $request)
    {
        $form = $this->createForm(ConferenceFormType::class, $conference);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($conference);
            $em->flush();
            $this->addFlash('success', 'Your conference proposal as been updated.');
            return $this->redirectToRoute('home');
        }

        return $this->render('conference/edit.html.twig',[
            'form' => $form->createView(),
            'conference' => $conference
        ]);
    }

}
