<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Event;
use App\Form\EventType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use App\Entity\Location;

class EventController extends AbstractController
{

    public function index(int $page): Response
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

    public function showCalendar(int $timestamp=null) : Response
    {

        // getting the monday corresponding to the given date
        $targetMonday = ($timestamp) ? new \DateTime('@'.$timestamp) : new \DateTime();
        $targetMonday->sub(new \DateInterval("P".(date('N',$targetMonday->getTimestamp())-1)."D"));
        $targetMonday->setTime(0,0,0);
        
         return $this->render('event/calendar.html.twig', [ 'targetMonday'=>$targetMonday ]);

    }

    public function loadEvents(int $timestamp=null):Response
    {
        // getting the monday corresponding to the given date
        $targetMonday = ($timestamp) ? new \DateTime('@'.$timestamp) : new \DateTime();
        $targetMonday->sub(new \DateInterval("P".(date('N',$targetMonday->getTimestamp())-1)."D"));
        $targetMonday->setTime(0,0,0);
        $targetSunday = new \DateTime($targetMonday->getTimestamp());
        $targetSunday->add(new \DateInterval('P7DT23H59M59S'));
        
        $em = $this->getDoctrine()->getEntityManager();
        $events = $em->getRepository(Event::class)->findbyDateInterval($targetMonday, $targetSunday);

        
		
		$response = new Response($serializer->serialize($events, 'json'));
        
        return $response;
    }



}
