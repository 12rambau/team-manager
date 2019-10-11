<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Location;
use App\Form\LocationType;
use Symfony\Component\HttpFoundation\RedirectResponse;


class AdminController extends AbstractController
{
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('admin/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    public function EditContact( Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();
        $contact = $em->getRepository(Location::class)->findOneByTag('contact');

        $form = $this->createForm(LocationType::class, $contact);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {

            //$em->persist($contact);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'The contact adress has been updated.');

            return new RedirectResponse($this->generateUrl('contact'));
        }

        return $this->render('admin/contactEdit.html.twig', [
            'form' => $form->createView(),
            'contact' => $contact,
        ]);
    }
}
