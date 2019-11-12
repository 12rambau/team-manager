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
use App\Form\TemplateSelectType;
use App\Utils\CalendarBox;
use App\Entity\Field;
use App\Entity\Position;
use App\Form\EventFieldsType;
use App\Entity\EventTag;
use App\Entity\PersonnalStat;
use App\Form\PersonnalStatType;
use App\Form\ScoreType;
use App\Entity\Score;
use Symfony\Component\HttpFoundation\RedirectResponse;



class EventController extends AbstractController
{

    public function index(int $page, Request $request): Response
    {
        $nbEventPerPage = 10;

        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository(Event::class)->findTenByUser(($page - 1) * $nbEventPerPage, $nbEventPerPage, $this->getUser());
        $nbEvent = $em->getRepository(Event::class)->countUserEvent($this->getuser());

        // TODO: code enhancement
        $participations = $em->getRepository(Participation::class)->findTenByUser(($page - 1) * 10, $this->getUser());
        $form = $this->createForm(ListParticipationType::class, ['participations' => $participations]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
        }

        return $this->render('event/index.html.twig', [
            'events' => $events,
            'form' => $form->createView(),
            'participations' => $participations,
            'nbEvent' => $nbEvent,
            'page' => $page,
            'nbEventPerPage' => $nbEventPerPage,
            'maxPage' => ceil($nbEvent / $nbEventPerPage)
        ]);
    }

    public function view(Event $event, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $participations = $event->getParticipations();
        $myParticipation = $em->getRepository(Participation::class)->FindByEventAndUsername($event, $this->getUser());

        $myFormInOut = $this->createForm(ParticipationType::class, $myParticipation);
        $formsInOut = $this->createForm(ListParticipationType::class, ['participations' => $participations]);

        if ($request->request->has('list_participation')) {
            $formsInOut->handleRequest($request);

            if ($formsInOut->isSubmitted() && $formsInOut->isValid())
                $em->flush();
        }

        if ($request->request->has('participation')) {
            $myFormInOut->handleRequest($request);

            if ($myFormInOut->isSubmitted() && $myFormInOut->isValid())
                $em->flush();
        }

        return $this->render('event/view.html.twig', [
            'event' => $event,
            'myFormInOut' => $myFormInOut->createView(),
            'myParticipation' => $myParticipation,
            'formsInOut' => $formsInOut->createView(),
            'participations' => $participations,
        ]);
    }

    public function add(Request $request): Response
    {
        $event = new Event();

        $em = $this->getDoctrine()->getManager();
        $tags = $em->getRepository(EventTag::class)->findActivated();

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($event);
            $em->flush();

            //TODO email the administrator to validate the new event 

            $request->getSession()->getFlashBag()->add('success', 'The post : ' . $event->getSlug() . ' has been added.');

            return new RedirectResponse($this->generateUrl('event-view', [
                'slug' => $event->getSlug()
            ]));
        }

        return $this->render('event/add.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
            'tags' => $tags
        ]);
    }

    public function edit(Event $event, Request $request): Response
    {

        if ($this->getUser() != $event->getAuthor()) 
            throw new AccessDeniedException('Not your Event');

        $form = $this->createForm(EventType::class, $event);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $tags = $em->getRepository(EventTag::class)->findActivated();

        if ($form->isSubmitted() && $form->isValid()) {

            $em->flush();

            //TODO email the administrator to validate the new event 

            $request->getSession()->getFlashBag()->add('success', 'The post : ' . $event->getSlug() . ' has been edited.');

            return new RedirectResponse($this->generateUrl('event-view', [
                'slug' => $event->getSlug()
            ]));
        }

        return $this->render('event/add.html.twig', [
            'form' => $form->createView(),
            'event' => $event,
            'tags' => $tags
        ]);
    }

    public function showCalendar(int $timestamp = null): Response
    {

        // getting the monday corresponding to the given date
        $targetMonday = ($timestamp) ? new \DateTime('@' . $timestamp) : new \DateTime();
        $targetMonday->sub(new \DateInterval("P" . (date('N', $targetMonday->getTimestamp()) - 1) . "D"));
        $targetMonday->setTime(0, 0, 0);

        //getting all the event concerning this period
        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository(Event::class)->findWeeklyEvent($targetMonday);

        //separate them for each day of the week 
        $weeklyEvents = CalendarBox::getWeeklyBoxes($targetMonday, $events);

        return $this->render('event/calendar.html.twig', [
            'targetMonday' => $targetMonday,
            'events' => $events,
            'weeklyEvents' => $weeklyEvents
        ]);
    }

    public function getEvents(Request $request): Response
    {
        $start = new \DateTime($request->query->get('start')); //change start into DateTime
        $end = new \DateTime($request->query->get('end')); //change end into dateTime

        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository(Event::class)->findbyDateInterval($start, $end);

        $data = $this->get('serializer')->serialize($events, 'json', ['groups' => ['calendar']]);

        return new Response($data);
    }

    public function plannification(Event $event, Request $request): Response
    {
        $templateForm = $this->createForm(TemplateSelectType::class);

        $fieldsForm = $this->createForm(EventFieldsType::class, $event);

        if ($request->request->has('template_select')) {
            $templateForm->handleRequest($request);

            if ($templateForm->isSubmitted() && $templateForm->isValid()) {
                $em = $this->getDoctrine()->getManager();

                $template = $em->getRepository(Field::class)->findOneById($request->get('template_select')['template']);

                //copying the template in the new field 
                $field = clone $template;
                $field->setName($event->getId() . "_" . $field->getName());
                $event->addField($field);

                $request->getSession()->getFlashBag()->add('success', 'The field : ' . $field->getName() . ' has been added to ' . $event->getName());
            }
        }

        if ($request->request->has("event_fields")) {
            $fieldsForm->handleRequest($request);

            if ($fieldsForm->isSubmitted() && $fieldsForm->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->flush();

                $request->getSession()->getFlashBag()->add('success', 'The event plannification : ' . $event->getSlug() . ' has been updated.');
            }
        }

        return $this->render('event/plannification.html.twig', [
            'event' => $event,
            'templateForm' => $templateForm->createView(),
            'fieldsForm' => $fieldsForm->createView()
        ]);
    }

    public function viewResult(Event $event, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $personnalStat = new PersonnalStat();
        $form = $this->createForm(PersonnalStatType::class, $personnalStat, ['inEvent' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            ($personnalStat->getTimer()) ? $personnalStat->setValue(null) : $personnalStat->setTime(null);
            $personnalStat->setEvent($event)->setPlayer($this->getUser());
            $em->persist($personnalStat);
            $em->flush();
        }

        $result = $event->getResult();

        $stats = $em->getRepository(PersonnalStat::class)->FindMyByEvent($event, $this->getUser());

        return $this->render('event/viewResult.html.twig', [
            'event' => $event,
            'stats' => $stats,
            'result' => $result,
            'form' => $form->createView()
        ]);
    }
}
