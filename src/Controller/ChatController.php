<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\ChatMessage;
use App\Form\ChatMessageType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
//use Symfony\Component\Routing\Generator\UrlGenerator;

class ChatController extends AbstractController
{
    public function index(): Response
    {				
        $em = $this->getDoctrine()->getEntityManager();
        $messages = $em->getRepository(ChatMessage::class)->findAll();
            
        return $this->render('chat/index.html.twig', [
            'messages' => $messages
        ]);
    }

    public function view(ChatMessage $message): Response
    {
        return $this->render('chat/view.html.twig', [
            'message' => $message
        ]);

    }

    public function add(Request $request): Response
    {
        $message = new ChatMessage();

        $form = $this->createForm(ChatMessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $message->setAuthor($this->getUser());

            $em = $this->getDoctrine()->getEntityManager();            
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

    public function edit(ChatMessage $message, Request $request)
    {
        $form = $this->createForm(ChatMessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getEntityManager();            
            $em->persist($message);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'The message : '.$message->getId().' has been modified.');

            return new RedirectResponse($this->generateUrl('chat-view', [
                'id' => $message->getId()
            ]));
        }

        return $this->render('chat/edit.html.twig', [
            'form' => $form->createView()
        ]);

    }

    public function delete(ChatMessage $message, Request $request):Response
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($message);
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'The message : '.$message->getId().' has been removed.');

        return $this->redirect($_SERVER['HTTP_REFERER']);

    }

}
