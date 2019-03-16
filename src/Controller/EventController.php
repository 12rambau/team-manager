<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Event;
use App\Form\EventType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Location;
use App\Entity\Participation;
use App\Form\ParticipationType;
use App\Form\ListParticipationType;
use App\Utils\CalendarBox;

class EventController extends AbstractController
{

    public function index(int $page, Request $request): Response
    {
        
        $em = $this->getDoctrine()->getEntityManager();
        $events = $em->getRepository(Event::class)->findTen(($page-1)*10);

        // TODO: code enhancement
        $participations = $em->getRepository(Participation::class)->findTenByUser(($page-1)*10, $this->getUser());
        $form = $this->createForm(ListParticipationType::class, ['participations'=> $participations]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $em->flush();
        }

        return $this->render('event/index.html.twig', [
            'events' => $events,
            'form' => $form->createView()
        ]);
    }

    public function view(Event $event, Request $request): Response
    {
        $em = $this->getDoctrine()->getEntityManager();

        $participations = $event->getParticipations();
        $myParticipation = $em->getRepository(Participation::class)->FindByEventAndUsername($event, $this->getUser());

        $myFormInOut = $this->createForm(ParticipationType::class, $myParticipation);
        $formsInOut = $this->createForm(ListParticipationType::class, ['participations' => $participations]);
 
        if ($request->request->has('list_participation'))
        {
            $formsInOut->handleRequest($request);

            if ($formsInOut->isSubmitted() && $formsInOut->isValid())
                $em->flush();
        }
     
        if ($request->request->has('participation') )
        {
            $myFormInOut->handleRequest($request);
                
            if ($myFormInOut->isSubmitted() && $myFormInOut->isValid())
                $em->flush();
        }
        
        return $this->render('event/view.html.twig', [
            'event' => $event,
            'myFormInOut' => $myFormInOut->createView(),
            'formsInOut' => $formsInOut->createView(),
            'participations' => $participations,
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

        //getting all the event concerning this period
        $em = $this->getDoctrine()->getEntityManager();
        $events = $em->getRepository(Event::class)->findWeeklyEvent($targetMonday);

        //separate them for each day of the week 
        $weeklyEvents = CalendarBox::getWeeklyBoxes($targetMonday,$events);
        
         return $this->render('event/calendar.html.twig', [ 
             'targetMonday'=>$targetMonday,
             'events'=>$events, 
             'weeklyEvents'=>$weeklyEvents
             ]);

    }

    public function getEvents(Request $request):Response
    {
        $start = new \DateTime($request->query->get('start')); //change start into DateTime
        $end = new \DateTime($request->query->get('end')); //change end into dateTime

        $em = $this->getDoctrine()->getEntityManager();
        $events = $em->getRepository(Event::class)->findbyDateInterval($start, $end);

        $data = $this->get('serializer')->serialize($events, 'json', ['groups' => ['calendar']]);

        return new Response($data);
    }

}
