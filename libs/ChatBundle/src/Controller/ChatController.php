<?php

namespace btba\ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use btba\ChatBundle\Entity\ChatMessage;
use btba\ChatBundle\Form\ChatMessageType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

class ChatController extends AbstractController
{

    public function add(Request $request, UploaderHelper $helper, CacheManager $cm): JsonResponse
    {

        $message = new ChatMessage();

        $form = $this->createForm(ChatMessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setAuthor($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($message);
            $em->flush();
        }

        $data = array();
        $data['content'] = $message->getContent();
        $data['username'] = $message->getAuthor()->getUsername();
        $data['date'] = date_format($message->getDate(), 'd/m h:i');
        $data['profilePicUrl'] = $cm->getBrowserPath($helper->asset($message->getAuthor()->getProfilePic(), 'imageFile'), 'userNavbar');


        return new JsonResponse($data);
    }

    public function list(int $nbMessage = 30): Response
    {

        $em = $this->getDoctrine()->getManager();
        $totalMessage = $em->getRepository(ChatMessage::class)->countAll();
        $messages = $em->getRepository(ChatMessage::class)->findBy([], null, $nbMessage, $totalMessage - $nbMessage);


        return $this->render('chat/list.html.twig', [
            'messages' => $messages,
            'nbMessage' => $nbMessage
        ]);
    }

    public function show(): Response
    {
        $message = new ChatMessage();

        $form = $this->createForm(ChatMessageType::class, $message);

        return $this->render('chat/show.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function test(): Response
    {
        return $this->render('@btbaChatBundle/test.html.twig');
    }
}
