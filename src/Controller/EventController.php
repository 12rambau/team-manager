<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Event;
use App\Form\EventType;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Participation;
use App\Form\ListParticipationType;
use App\Form\TemplateSelectType;
use App\Entity\EventTag;
use App\Entity\FieldTemplate;
use App\Entity\Player;
use App\Form\ParticipationStatsType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use App\Form\EventFieldsType;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\Security\Core\Security;

class EventController extends AbstractController
{

    public function index(int $page, Request $request): Response
    {
        $nbEventPerPage = 10;

        $em = $this->getDoctrine()->getManager();

        //fetch the events
        $events = $em->getRepository(Event::class)->findBy(
            [],
            ['start' => 'desc'],
            $nbEventPerPage,
            ($page - 1) * $nbEventPerPage
        );
        $nbEvent = $em->getRepository(Event::class)->countAll();

        //check the user participations 
        $user = $this->getUser();
        $participations = [];
        foreach ($events as $event) {
            foreach ($user->getPlayers() as $player) {
                $participation = $em->getRepository(Participation::class)->findOneBy([
                    'event' => $event,
                    'player' => $player
                ]);
                if ($participation) {
                    array_push($participations, $participation);
                    break;
                } else {
                    if ($event->getTeam() == $player->getTeam()) {

                        $participation = new Participation();
                        $participation->setEvent($event);
                        $participation->setPlayer($player);
                        $em->persist($participation);
                        array_push($participations, $participation);
                        break;
                    }
                }
            }
        }
        $em->flush();

        // TODO: code enhancement
        $form = $this->createForm(ListParticipationType::class, ['participations' => $participations]);

        //Security as the form is managed through ajax
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
        }

        $tags = $em->getRepository(EventTag::class)->findBy(['active' => true]);

        return $this->render('event/index.html.twig', [
            'tags' => $tags,
            'events' => $events,
            'form' => $form->createView(),
            'participations' => $participations,
            'nbEvent' => $nbEvent,
            'page' => $page,
            'nbEventPerPage' => $nbEventPerPage,
            'maxPage' => ceil($nbEvent / $nbEventPerPage)
        ]);
    }

    public function updateIndex(int $page, Request $request): JsonResponse
    {
        //TODO work only for 10 elements per page 
        $nbEventPerPage = 10;

        $em = $this->getDoctrine()->getManager();

        // TODO: code enhancement
        $events = $em->getRepository(Event::class)->findBy(
            [],
            ['start' => 'desc'],
            $nbEventPerPage,
            ($page - 1) * $nbEventPerPage
        );

        $user = $this->getUser();
        $participations = [];
        foreach ($events as $event) {
            foreach ($user->getPlayers() as $player) {
                $participation = $em->getRepository(Participation::class)->findOneBy([
                    'event' => $event,
                    'player' => $player
                ]);
                if ($participation) {
                    array_push($participations, $participation);
                    break;
                }
            }
        }

        $form = $this->createForm(ListParticipationType::class, ['participations' => $participations]);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
            $em->flush();

        return new JsonResponse();
    }

    public function view(Event $event, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        //get the current player
        $player = $em->getRepository(Player::class)->findOneBy([
            'user' => $this->getUser(),
            'team' => $event->getTeam()
        ]);


        $participations = $event->getParticipations();
        $myParticipation = $em->getRepository(Participation::class)->findOneBy([
            'player' => $player,
            'event' => $event
        ]);
        $formsInOut = $this->createForm(ListParticipationType::class, ['participations' => $participations]);

        //get the index of the current user 
        foreach ($participations as $key => $participation) {
            if ($participation->getPlayer() == $player) {
                $userIndex = $key;
                break;
            }
        }

        //security as the form is ajax handled
        $formsInOut->handleRequest($request);
        if ($formsInOut->isSubmitted() && $formsInOut->isValid())
            $em->flush();

        return $this->render('event/view.html.twig', [
            'event' => $event,
            'myParticipation' => $myParticipation,
            'formsInOut' => $formsInOut->createView(),
            'participations' => $participations,
            'userIndex' => $userIndex
        ]);
    }

    public function updateParticipation(Event $event, Request $request): JsonResponse
    {
        $em = $this->getDoctrine()->getManager();
        $participations = $event->getParticipations();
        $formsInOut = $this->createForm(ListParticipationType::class, ['participations' => $participations]);

        $formsInOut->handleRequest($request);

        if ($formsInOut->isSubmitted() && $formsInOut->isValid())
            $em->flush();

        return new JsonResponse();
    }

    public function add(Request $request): Response
    {
        $event = new Event();

        $em = $this->getDoctrine()->getManager();
        $tags = $em->getRepository(EventTag::class)->findByActive(true);

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
        $tags = $em->getRepository(EventTag::class)->findByActive(true);

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
        $em = $this->getDoctrine()->getManager();
        $tags = $em->getRepository(EventTag::class)->findBy(['active' => true]);

        return $this->render('event/calendar.html.twig', [
            'tags' => $tags
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
        $this->denyAccessUnlessGranted('ROLE_USER', null, 'you cannot access the plannification page without being a team member');

        //create the two forms
        $templateForm = $this->createForm(TemplateSelectType::class, $event);
        $fieldsForm = $this->createForm(EventFieldsType::class, $event);

        $em = $this->getDoctrine()->getManager();

        return $this->render('event/plannification.html.twig', [
            'event' => $event,
            'templateForm' => $templateForm->createView(),
            'fieldsForm' => $fieldsForm->createView()
        ]);
    }

    /**
     * @Entity("event", expr="repository.find(event_id)")
     * @Entity("template", expr="repository.find(template_id)")
     */
    public function updateTemplate(Event $event, FieldTemplate $template = null, int $index, Request $request, UploaderHelper $helper, CacheManager $cm): JsonResponse
    {
        $form = $this->createForm(TemplateSelectType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }

        //return the new field template information
        //TODO do it with the symfony serializer 
        $data = array();

        if ($template) {
            $data['image'] = $cm->getBrowserPath($helper->asset($template->getImage(), 'imageFile'), 'field');
            //get the field with his index in the field list
            $event = $em->getRepository(Event::class)->find($event->getId());
            $field = $event->getFields()->get($index);
            $data['fieldId'] = $field->getId(); //TODO fid an intelligent way to get Id
            $data['positions'] = array();
            foreach ($template->getPositions() as $key => $position) {
                $data['positions'][$key] = array();
                $data['positions'][$key]['id'] = $position->getId();
                $data['positions'][$key]['vertical'] = $position->getVertical();
                $data['positions'][$key]['horizontal'] = $position->getHorizontal();
            }
        }


        return new JsonResponse($data);
    }

    public function removeTemplate(Event $event, Request $request): JsonResponse
    {
        $form = $this->createForm(TemplateSelectType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }


        return new JsonResponse();
    }

    public function updatePositions(Event $event, Request $request): JsonResponse
    {
        $form = $this->createForm(EventFieldsType::class, $event);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
        }

        return new JsonResponse();
    }

    public function viewResult(Event $event, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        //fetch the current player 
        $player = $em->getRepository(Player::class)->findOneBy([
            'user' => $this->getUser(),
            'team' => $event->getTeam()
        ]);

        //fetch the participation
        $participation = $em->getRepository(Participation::class)->FindOneBy([
            'event' => $event,
            'player' => $player
            ]); 

        
        $form = $this->createForm(ParticipationStatsType::class, $participation);


        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
            $em->flush();

        $result = $event->getResult();

        return $this->render('event/viewResult.html.twig', [
            'event' => $event,
            'result' => $result,
            'form' => $form->createView()
        ]);
    }
}
