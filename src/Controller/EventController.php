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
use App\Form\ParticipationStatsType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;



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
        $em = $this->getDoctrine()->getManager();

        // TODO: code enhancement
        $participations = $em->getRepository(Participation::class)->findTenByUser(($page - 1) * 10, $this->getUser());
        $form = $this->createForm(ListParticipationType::class, ['participations' => $participations]);


        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
            $em->flush();

        return new JsonResponse();
    }

    public function view(Event $event, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $participations = $event->getParticipations();
        $myParticipation = $em->getRepository(Participation::class)->FindByEventAndUser($event, $this->getUser());
        $formsInOut = $this->createForm(ListParticipationType::class, ['participations' => $participations]);

        //get the index of the current user 
        foreach ($participations as $key => $participation) {
            if ($participation->getUser() == $this->getUser())
                $userIndex = $key;
        }

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

        //TODO specific status to access this page

        //create the two forms
        $templateForm = $this->createForm(TemplateSelectType::class, $event);
        //$fieldsForm = $this->createForm(EventFieldsType::class, $event);

        $em = $this->getDoctrine()->getManager();

        if ($request->request->has('template_select')) {
            $templateForm->handleRequest($request);

            if ($templateForm->isSubmitted() && $templateForm->isValid()) {

                $template = $em->getRepository(FieldTemplate::class)->findOneById($request->get('template_select')['template']);

                //copying the template in the new field 
                $field = clone $template;
                $field->setName($event->getId() . "_" . $field->getName());
                $event->addField($field);
            }
        }

        /*if ($request->request->has("event_fields")) {
            $fieldsForm->handleRequest($request);

            if ($fieldsForm->isSubmitted() && $fieldsForm->isValid()) {

                $em = $this->getDoctrine()->getManager();
                $em->flush();

            }
        }*/

        return $this->render('event/plannification.html.twig', [
            'event' => $event,
            'templateForm' => $templateForm->createView(),
            //'fieldsForm' => $fieldsForm->createView()
        ]);
    }

    /**
     * @Entity("event", expr="repository.find(event_id)")
     * @Entity("template", expr="repository.find(template_id)")
     */
    public function updateTemplate(Event $event, FieldTemplate $template=null, Request $request, UploaderHelper $helper, CacheManager $cm): JsonResponse
    {
        $templateForm = $this->createForm(TemplateSelectType::class, $event);

        $em = $this->getDoctrine()->getManager();

        $templateForm->handleRequest($request);

        if ($templateForm->isSubmitted() && $templateForm->isValid()) {

            // TODO see where to update the field (I imagine in the entity )
            $em->flush();
        }

        //return the new field template information
        //TODO do it with the symfony serializer 
        $data = array();

        if ($template) {
            $data['image'] = $cm->getBrowserPath($helper->asset($template->getImage(), 'imageFile'), 'field');
            $data['positions'] = array();
            foreach ($template->getPositions() as $key => $position) {
                $data['positions'][$key] = array();
                $data['positions'][$key]['vertical'] = $position->getVertical();
                $data['positions'][$key]['horizontal'] = $position->getHorizontal();
            }
        }


        return new JsonResponse($data);
    }

    public function viewResult(Event $event, Request $request): Response
    {
        $em = $this->getDoctrine()->getManager();

        $participation = $em->getRepository(Participation::class)->FindByEventAndUser($event, $this->getUser());
        $form = $this->createForm(ParticipationStatsType::class, $participation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //no need for persist with the cascade in the entity definition
            $em->flush();
        }

        $result = $event->getResult();

        return $this->render('event/viewResult.html.twig', [
            'event' => $event,
            'result' => $result,
            'form' => $form->createView()
        ]);
    }
}
