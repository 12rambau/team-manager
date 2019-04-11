<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Event;
use Symfony\Component\HttpFoundation\Response;

class AsideController extends AbstractController
{
    public function event(Event $event):Response
    {
        $nbEvents = 2;

        $em = $this->getDoctrine()->getManager();
        $events = $em->getRepository(Event::class)->findAround($nbEvents, $event);
                
        return $this->render('aside/events.html.twig',[
            'currentEvent' => $event,
            'events' => $events
        ]);
    }
}
