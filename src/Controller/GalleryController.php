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
    public function index($page): Response
    {
        $limit = 10;

        $em = $this->getDoctrine()->getManager();
        $galleries = $em->getRepository(Gallery::class)->findBy([],[],$limit, ($page-1)*$limit);

        return $this->render('gallery/index.html.twig', [
            'galleries' => $galleries,
        ]);
    }

    public function add(Gallery $gallery = null, Request $request): Response
    {
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

            //return new RedirectResponse($this->generateUrl('gallery-view', [
            //    'name' => $gallery->getName()
            //]));
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
