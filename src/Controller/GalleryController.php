<?php

namespace App\Controller;

use App\Entity\Gallery;
use App\Form\GalleryType;
use App\Entity\Image;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class GalleryController extends AbstractController
{
    public function index(): Response
    {
        $em = $this->getDoctrine()->getManager();
        $galleries = $em->getRepository(Gallery::class)->findAll();

        $filtered_galleries = array();
        foreach ($galleries as $gallery) {
            if (!preg_match("/^result_/",$gallery->getName()) && count($gallery->getImages()))
                array_push($filtered_galleries, $gallery);
        }

        //TODO add pagination
        return $this->render('gallery/index.html.twig', [
            'galleries' => $filtered_galleries,
        ]);
    }

    public function add(Gallery $gallery = null, Request $request): Response
    {

        //TODO improve the image form with modern js and css
        $gallery = ($gallery)?$gallery:new Gallery();

        $form = $this->createForm(GalleryType::class, $gallery);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $em = $this->getDoctrine()->getManager();
            
            //add every images
            foreach ($request->files->get('gallery')['files'] as $file) 
            {
                $image = new Image();
                $image->setImageFile($file);
                $gallery->addImage($image);
                $em->persist($image);
            }
            
            $em->persist($gallery);
            $em->flush();

            // TODO send an email to the administrator

            $request->getSession()->getFlashBag()->add('success', 'The gallery : '.$gallery->getName().' has been added.');
        }

        return $this->render('gallery/add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function delete(Gallery $gallery, Request $request):Response
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($gallery);
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'The gallery : '.$gallery->getName().' has been removed.');

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    public function view(Gallery $gallery):Response
    {
        return $this->render('gallery/view.html.twig', [
            'gallery' => $gallery
        ]);
    }
}
