<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\ChatMessage;
use App\Form\ChatMessageType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ChatController extends AbstractController
{

    public function add(Request $request): Response
    {

        //TODO don't render the chat view but answer an ajax solicitation

        $message = new ChatMessage();

        $form = $this->createForm(ChatMessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $message->setAuthor($this->getUser());

            $em = $this->getDoctrine()->getManager();            
            $em->persist($message);
            $em->flush();

            return new RedirectResponse($this->generateUrl('chat-view', [
                'id' => $message->getId()
            ]));
        }

        return $this->render('chat/add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function list():Response
    {
        $em = $this->getDoctrine()->getManager();
        $messages = $em->getRepository(ChatMessage::class)->findBy([],null,30,0);
    

        return $this->render('chat/list.html.twig', [
            'messages' => $messages
        ]);
    }

}
