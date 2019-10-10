<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use App\DataFixtures\AppFixtures;

class UserController extends AbstractController
{
    public function edit(User $user, Request $request):Response
    {

        //double chek user identity
        if ($user != $this->getUser())
        {
            // TODO throw an error
        }

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    public function view(User $user):Response
    {
        //redirect to edit if it's the current user
        if ($user === $this->getUser())
            return new RedirectResponse($this->generateUrl('profile-edit', ['username' => $user->getUsername()]));

        return $this->render('user\view.html.twig', [
            'user' => $user
        ]);
    }

    public function userMessages():Response
    {
        return $this->render('debug.html.twig');
    }

    public function userPost():Response
    {
        return $this->render('debug.html.twig');
    }

    public function userevents():Response
    {
        return $this->render('debug.html.twig');
    }

    public function resetProfilePic(User $user):Response
    {

        //get the root Directory
        $rootDir = $this->getParameter('kernel.project_dir');
        //$rootDir = $this->container->get('kernel')->getRootDir();
        $gender = $user->getGender()? 'male':'female';

        $image = AppFixtures::manualImage('no-profile-pic-'.$gender.'.jpg', $rootDir);

        $user->setProfilePic($image);

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        //return $this->render('debug.html.twig');
        return new RedirectResponse($this->generateUrl('profile-edit', ['username' => $user->getUsername()]));



    }
}
