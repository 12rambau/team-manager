<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Location;
use App\Entity\Partner;
use App\Entity\Team;
use App\Entity\Social;
use App\Form\ContactType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Service\EmailService;

class DefaultController extends AbstractController
{
    public function home()
    {
        $em = $this->getDoctrine()->getManager();

        $contact = $em->getRepository(Location::class)->findOneByTag('contact');
        $partners = $em->getRepository(Partner::class)->findAll();
        $teams = $em->getRepository(Team::class)->findAll();
        $socials = $em->getRepository(Social::class)->findAll();

        return $this->render('default/home.html.twig', [
            'contact' => $contact,
            'partners' => $partners,
            'teams' => $teams,
        ]);
    }

    public function Social(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $socials = $em->getRepository(Social::class)->findAll();

        return $this->render('default/social.html.twig', [
            'socials' => $socials,
        ]);

    }

    public function ContactAdress(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository(Location::class)->findOneByTag('contact');

        return $this->render('default/contact-adress.html.twig', [
            'contact' => $contact,
        ]);
    }

    public function contact(Request $request, EmailService $emailService): Response
    {
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository(Location::class)->findOneByTag('contact');


        //create the form
        $form = $this->createForm(ContactType::class, null);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($emailService->sendEmail($form->getData())) {
                $request->getSession()->getFlashBag()->add('success', 'Email sent.');

                //empty the form 
                $form = $this->createForm(ContactType::class, null);
            } else {
                $request->getSession()->getFlashBag()->add('danger', 'An error occured.');
            }
        }

        return $this->render('default/contact.html.twig', [
            'contact' => $contact,
            'form' => $form->createView()
        ]);
    }

    public function nav ($navId): Response
    {
        $em = $this->getDoctrine()->getmanager();
        $teams = $em->getRepository(Team::class)->findAll();

        return $this->render('default/navBar.html.twig', [
            'teams' => $teams,
            'navId' => $navId
        ]);
    }
}
