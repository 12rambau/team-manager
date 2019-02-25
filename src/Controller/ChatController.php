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
    public function Index($page): Response
    {				
        $em = $this->getDoctrine()->getEntityManager();
        $messages = $em->getRepository(ChatMessage::class)->findSome(($page-1)*40,40);
            
        return $this->render('chat/index.html.twig', [
            'messages' => $messages
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

    public function delete(ChatMessage $message, Request $request):Response
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($message);
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'The message : '.$message->getId().' has been removed.');

        // TODO code refactoring
        return $this->redirect($_SERVER['HTTP_REFERER']);

    }

    public function list():Response
    {
        $em = $this->getDoctrine()->getEntityManager();
        $messages = $em->getRepository(ChatMessage::class)->findSome(0, 30);

        return $this->render('chat/list.html.twig', [
            'messages' => $messages
        ]);
    }

}
