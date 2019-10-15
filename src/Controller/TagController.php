<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\EventTag;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use App\Form\TagType;

class TagController extends AbstractController
{

    public function index(Request $request): Response
    {
        $tag = new EventTag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted() && $form->isValid()) {

            $em->persist($tag);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'The Tag : ' . $tag->getName() . ' has been added.');
        }

        $listTags = $em->getRepository(EventTag::class)->findAll();

        return $this->render('tag/index.html.twig', [
            'listTags' => $listTags,
            'form' => $form->createView()
        ]);
    }

    public function delete(EventTag $tag, Request $request): RedirectResponse
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($tag);
        $em->flush();

        $request->getSession()->getFlashBag()->add('danger', 'The tag : ' . $tag->getName() . ' has been deleted.');

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function activate(EventTag $tag, Request $request): RedirectResponse
    {
        $tag->setActive(!$tag->getActive());

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        $request->getSession()->getFlashBag()->add('warning', 'The tag : ' . $tag->getName() . ' has been '.(($tag->getActive())?"activated":"deactivated"));

        return $this->redirect($_SERVER['HTTP_REFERER']);

    }
}
