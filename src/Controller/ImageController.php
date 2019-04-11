<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Image;
use App\Form\ImageType;


class ImageController extends AbstractController
{
    public function index($page): Response
    {
        $em = $this->getDoctrine()->getManager();
        $images = $em->getRepository(Image::class)->findAll();
        
        return $this->render('image/index.html.twig', [
            'images' => $images
        ]);
    }

    public function add(Request $request):Response 
    {
        $image = new Image();

        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($image);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'The image : '.$image->getFileName().' has been added.');

            return new RedirectResponse($this->generateUrl('image-index'));
        }

        return $this->render('image/add.html.twig', [
            'form' => $form->createView()
        ]);
        
    }

    public function edit(Image $image, Request $request):Response
    {
        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'The image : '.$image->getFileName().' has been edited.');

            return new RedirectResponse($this->generateUrl('image-index'));
        }

        return $this->render('image/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    public function delete(Image $image, Request $request):Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($image);
        $em->flush();

        $request->getSession()->getFlashBag()->add('danger', 'The image : '.$image->getFileName().' has been deleted.');

        return new RedirectResponse($this->generateUrl('image-index'));
    }

    public function view(Image $image):Response
    {
        return $this->render('image/view.html.twig', [
            'image' => $image
        ]); 
    }
}
