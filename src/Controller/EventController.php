<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Event;
use App\Form\EventType;
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
            'event' => $event
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

            $request->getSession()->getFlashBag()->add('success', 'The post : '.$event->getSlug().' has been added.');

            return new RedirecResponse($this->generateUrl('event-view', [
                'slug' => $event->getSlug()
            ]));
        }

        return $this->render('event/add.html.twig', [
            'form' => $form->createView()
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

            $request->getSession()->getFlashBag()->add('success', 'The post : '.$event->getSlug().' has been edited.');

            return new RedirecResponse($this->generateUrl('event-view', [
                'slug' => $event->getSlug()
            ]));
        }

        return $this->render('event/edit.html.twig', [
            'form' => $form->createView(),
            'event' => $event
        ]);
    }

    public function delete(Event $event, Request $request): Response
    {
        $em = $this->getDoctrine()->getEntitymanager();
        $em->remove($event);
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'The post : '.$event->getSlug().' has been deleted.');

        return $this->redirect($request->headers->get('referer'));
    }

    public function activate(Event $event, Request $request): Response
    {
        //TODO shouldn't be allowed to anyone

        $event->setActive(true);

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($event);
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'The post : '.$event->getSlug().' has been activated.');
        
        return $this->redirect($request->headers->get('referer'));
    }

    public function deactivate(Event $event, Request $request): Response
    {
        //TODO shouldn't be allowed to anyone

        $event->setActive(false);

        $em = $this->getDoctrine()->getEntityManager();
        $em->persist($event);
        $em->flush();
        
        $request->getSession()->getFlashBag()->add('success', 'The post : '.$event->getSlug().' has been deactivated.');

        return $this->redirect($request->headers->get('referer'));
    }

    public function showCalendar($date=null) : Response
    {

        		//on récupère la date d'aujourd'hui et la date cible 
		$now = new \DateTime();
		if($date == null){ $date = $now->getTimestamp();}
		$dateCible = new \DateTime();
		$dateCible->setTimestamp($date);
		
		//on récupère le lundi de la semaine
		$lundi = new \DateTime();
		$jsemaine = strftime('%u', $date)-1; //-1 car le jour 0 st dimanche dans le php
		$lundi->setTimestamp($date);
		$lundi->sub(new \DateInterval("P".$jsemaine."D"));
		
		//on tabule ls jours de la semaine 
		$joursSemaine = array ();
		for ($i = 0; $i < 7; $i++)
		{
			$joursSemaine[$i] = new \DateTime();
			$joursSemaine[$i]->setTimestamp($lundi->getTimestamp());
			$joursSemaine[$i]->add(new \DateInterval("P".$i."D"));
			$joursSemaine[$i]->setTime(0, 0, 0);
		}
		
		//lundi suivant et lundi precedent
		$lundiPrec = new \DateTime();
		$lundiPrec->setTimestamp($lundi->getTimestamp());
		$lundiPrec->sub(new \DateInterval("P7D"));
		$lundiSui = new \DateTime();
		$lundiSui->setTimestamp($lundi->getTimestamp());
		$lundiSui->add(new \DateInterval("P7D"));
        
         return $this->render('calendar/calendar.html.twig', [
            'now'			=>	$now,
            'joursSemaine'	=>	$joursSemaine,
            'lundiPrec'		=>	$lundiPrec,
            'lundiSui'		=>	$lundiSui,
            'dateCible'		=>	$dateCible
            ]);

    }

    public function header($date=null) : Response
    {
        //on récupère la date d'aujourd'hui et la date cible 
		$now = new \DateTime();
		if($date == null){ $date = $now->getTimestamp();}
		$dateCible = new \DateTime();
		$dateCible->setTimestamp($date);
		
		//on récupère le lundi de la semaine
		$lundi = new \DateTime();
		$jsemaine = strftime('%u', $date)-1; //-1 car le jour 0 st dimanche dans le php
		$lundi->setTimestamp($date);
		$lundi->sub(new \DateInterval("P".$jsemaine."D"));
		
		//on tabule ls jours de la semaine 
		$joursSemaine = array ();
		for ($i = 0; $i < 7; $i++)
		{
			$joursSemaine[$i] = new \DateTime();
			$joursSemaine[$i]->setTimestamp($lundi->getTimestamp());
			$joursSemaine[$i]->add(new \DateInterval("P".$i."D"));
			$joursSemaine[$i]->setTime(0, 0, 0);
		}
		
		//lundi suivant et lundi precedent
		$lundiPrec = new \DateTime();
		$lundiPrec->setTimestamp($lundi->getTimestamp());
		$lundiPrec->sub(new \DateInterval("P7D"));
		$lundiSui = new \DateTime();
		$lundiSui->setTimestamp($lundi->getTimestamp());
		$lundiSui->add(new \DateInterval("P7D"));
		
		//on retourne la vue 
		return $this->render('calendar\header.html.twig', array(
				'now'			=>	$now,
				'joursSemaine'	=>	$joursSemaine,
				'lundiPrec'		=>	$lundiPrec,
				'lundiSui'		=>	$lundiSui,
				'dateCible'		=>	$dateCible
		));
    }



}
