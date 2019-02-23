<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Event;
use App\Entity\EventType;
use Symfony\Component\HttpFoundation\Request;

class EventController extends AbstractController
{

    public function index(): Response
    {
        $em = $this->getDoctrine()->getEntityManager();
        $listEvents = $em->getRepository(Event::class)->findTen(($page-1)*10);

        return $this->render('event/index.html.twig', [
            'listEvents' => $listEvents
        ]);
    }

    public function view(Event $event): Response
    {
        return $this->render('event/view.html.twig', [
            'slug' => $event->getSlug()
        ]);
    }

    public function add(Request $request): Response
    {
        $event = new Event();

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($event);
            $em->flush();

            //TODO email the administrator to validate the new event 

            $request->getSession()->getFlashBag()->add('success', 'The post : '.$post->getSlug().' has been added.');

            return new RedirecResponse($this->urlGenerator->generate('event-view', [
                'slug' => $event->getSlug()
            ]));
        }

        return $this->render('event/add.html.twig', [
            'form' => $form->creatView()
        ]);
    }

    public function edit(Event $event, Request $request): Response
    {
        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($event);
            $em->flush();

            //TODO email the administrator to validate the new event 

            $request->getSession()->getFlashBag()->add('success', 'The post : '.$post->getSlug().' has been edited.');

            return new RedirecResponse($this->urlGenerator->generate('event-view', [
                'slug' => $event->getSlug()
            ]));
        }

        return $this->render('event/edit.html.twig', [
            'form' => $form->creatView()
        ]);
    }

    public function delete(Event $event, Request $request): Response
    {
        $em = $this->getDoctrine()->getEntitymanager();
        $em->remove($event);
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'The post : '.$post->getSlug().' has been deleted.');

        return $this->redirect($request->headers->get('referer'));
    }

    public function activate(Event $event, Request $request): Response
    {
        //TODO shouldn't be allowed to anyone

        $event->setActive(true);

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($event);
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'The post : '.$post->getSlug().' has been activated.');
        
        return $this->redirect($request->headers->get('referer'));
    }

    public function deactivate(Event $event, Request $request): Response
    {
        //TODO shouldn't be allowed to anyone

        $event->setActive(false);

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($event);
        $em->flush();
        
        $request->getSession()->getFlashBag()->add('success', 'The post : '.$post->getSlug().' has been deactivated.');

        return $this->redirect($request->headers->get('referer'));
    }



}
