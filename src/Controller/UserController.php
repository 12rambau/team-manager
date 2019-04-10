<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;

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
            $em = $this->getDoctrine()->getEntityManager();
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
}
