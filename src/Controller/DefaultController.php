<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Location;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Service\EmailService;

class DefaultController extends AbstractController
{
    public function home()
    {
        return $this->render('default/home.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    public function contact(Request $request, EmailService $emailService): Response
    {
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository(Location::class)->findOneByTag('contact');


        //create the form
        $form = $this->createForm(ContactType::class ,null);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) 
        {
            if ($emailService->sendEmail($form->getData())) 
            {
                $request->getSession()->getFlashBag()->add('success', 'Email send.');
            } else {
                $request->getSession()->getFlashBag()->add('danger', 'An error occured.');
            }
        }

        return $this->render('default/contact.html.twig', [
            'contact' => $contact,
            'form' => $form->createView()
        ]);
    }
}
