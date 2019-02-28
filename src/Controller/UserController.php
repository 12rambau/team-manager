<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
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
            'form' => $form->createView()
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
