<?php

namespace btba\ChatBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use btba\ChatBundle\Form\ChatMessageType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

class ChatController extends AbstractController
{
    public function add(Request $request, UploaderHelper $helper, CacheManager $cm): JsonResponse
    {

        $message = new $this->message_class();
        //TODO check if the class inherited from the message Model

        $form = $this->createForm(ChatMessageType::class, $message);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            //TODO check if the user inherited from the Author class 

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
        $totalMessage = $em->getRepository($this->message_class)->countAll();
        $messages = $em->getRepository($this->message_class)->findBy([], null, $nbMessage, $totalMessage - $nbMessage);


        return $this->render('@BtbaChat/list.html.twig', [
            'messages' => $messages,
            'nbMessage' => $nbMessage
        ]);
    }

    public function show(): Response
    {
        $message = new $this->message_class();
        //TODO check if the message class enherited fromm message

        $form = $this->createForm(ChatMessageType::class, $message);

        return $this->render('@BtbaChat/show.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    public function test(): Response
    {
        return $this->render('@BtbaChat/test.html.twig');
    }
}
