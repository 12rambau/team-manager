<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Field;
use App\Form\FieldType;
use App\Form\TemplateType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;

class FieldController extends AbstractController
{
    public function index($page): Response
    {
        $em = $this->getDoctrine()->getEntityManager();
        $templates = $em->getRepository(Field::class)->findAllTemplate();
        
        return $this->render('field/index.html.twig', [
            'templates' => $templates
        ]);
    }

    public function add(Request $request):Response 
    {
        $template = new Field();

        $form = $this->createForm(TemplateType::class, $template);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $template->setName("template_".$template->getName());
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($template);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'The template : '.$template->getSlug().' has been added.');

            return new RedirectResponse($this->generateUrl('template-index'));
        }

        return $this->render('field/add.html.twig', [
            'form' => $form->createView(),
            'template' => $template
        ]);
        
    }

    public function edit(Field $template, Request $request):Response
    {
        $form = $this->createForm(TemplateType::class, $template);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getEntityManager();
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'The template : '.$template->getSlug().' has been edited.');

            return new RedirectResponse($this->generateUrl('template-index'));
        }

        return $this->render('field/edit.html.twig', [
            'form' => $form->createView(),
            'template' => $template
        ]);
    }

    public function delete(Field $field, Request $request):Response
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($field);
        $em->flush();

        $request->getSession()->getFlashBag()->add('danger', 'The template : '.$field->getSlug().' has been deleted.');

        return new RedirectResponse($this->generateUrl('template-index'));
    }

    public function view(Field $template):Response
    {
        return $this->render('field/view.html.twig', [
            'template' => $template
        ]); 
    }
}
