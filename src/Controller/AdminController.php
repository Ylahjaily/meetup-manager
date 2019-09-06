<?php

namespace App\Controller;

use App\Entity\Conference;
use App\Entity\User;
use App\Form\ConferenceFormType;
use App\Form\UserRegisterType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }


    /**
     * @Route("/admin/conference", name="app_conference_admin")
     */
    public function getConferences()
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

        return $this->render('admin/conference.html.twig', [
            'conferences' => $conferences,
            'average' => $averageNote
        ]);
    }

    /**
     * @Route("/admin/conference/{id}", name="app_conference_id_admin")
     * @ParamConverter("conference", class="App\Entity\Conference")
     */
    public function getOneConference(Conference $conference)
    {

        if(is_null($conference))
        {
            throw new NotFoundHttpException();
        }

        return $this->render('admin/conference_id.html.twig',[
            'conference' => $conference
        ]);
    }

    /**
     * @Route("/admin/users", name="app_users_admin")
     */
    public function getUser()
    {
        $em = $this->getDoctrine()->getRepository(User::class);
        $users = $em->findAll();

        return $this->render('admin/users.html.twig',[
            'users' => $users
        ]);
    }

    /**
     * @Route("/admin/user/{id}", name="app_user_id_admin")
     * @ParamConverter("user", class="App\Entity\User")
     */
    public function getOneUser(User $user)
    {

        if(is_null($user))
        {
            throw new NotFoundHttpException();
        }

        return $this->render('admin/user_id.html.twig',[
            'user' => $user
        ]);
    }

    /**
     * @Route("/admin/user/{id}/remove", name="app_user_remove_admin")
     * @ParamConverter("user", class="App\Entity\User")
     */
    public function removeOneUser(User $user)
    {
        if(is_null($user))
        {
            throw new NotFoundHttpException();
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($user);
        $em->flush();
        $this->addFlash('success', 'This user has been Deleted.');
        return $this->redirectToRoute('app_users_admin');
    }

    /**
     * @Route("/admin/user/{id}/edit", name="app_user_edit_admin")
     * @ParamConverter("user", class="App\Entity\User")
     */
    public function editUser(User $user, Request $request)
    {
        $form = $this->createForm(UserRegisterType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'This user has been updated.');
            return $this->redirectToRoute('app_users_admin');
        }

        return $this->render('security/register.html.twig',[
            'form' => $form->createView(),
            'conference' => $user
        ]);
    }

}
