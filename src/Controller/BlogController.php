<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\BlogPost;
use App\Form\BlogPostType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;


class BlogController extends AbstractController
{
    public function index($page): Response
    {				
        $em = $this->getDoctrine()->getEntityManager();
        $posts = $em->getRepository(BlogPost::class)->findTen(($page-1)*10);
            
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'posts' => $posts
        ]);
    }

    public function view(BlogPost $post): Response
    {
        return $this->render('blog/view.html.twig', array(
            'post' => $post
        ));

    }

    public function add(Request $request): Response
    {
        $post = new BlogPost();

        $form = $this->createForm(BlogPostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $post->setAuthor($this->getUser());

            $em = $this->getDoctrine()->getEntityManager();            
            $em->persist($post);
            $em->flush();

            // TODO send an email to the administrator

            return new RedirectResponse($this->generateUrl('blog-view', array(
                'slug' => $post->getSlug()
            )));
        }

        return $this->render('blog/add.html.twig', array(
            'form' => $form->createView()
        ));
    }

    public function edit(BlogPost $post, Request $request)
    {
        $form = $this->createForm(BlogPostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $post->setEditDate(new \DateTime());
            $post->setActive(false);

            $em = $this->getDoctrine()->getEntityManager();            
            $em->persist($post);
            $em->flush();

            // TODO send an email to the administrator

            return new RedirectResponse($this->generateUrl('blog-view', [
                'slug' => $post->getSlug()
                // TODO bug, le slug ne change pas.
            ]));
        }

        return $this->render('blog/edit.html.twig', [
            'form' => $form->createView(),
            'post' => $post
        ]);

    }

    public function delete(BlogPost $post, Request $request):Response
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($post);
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'The post : '.$post->getSlug().' has been removed.');

        return $this->redirect($_SERVER['HTTP_REFERER']);

    }

    public function activate(BlogPost $post, Request $request) : Response
    {

        //TODO shouldn't be allowed to anyone 
        $em = $this->getDoctrine()->getEntityManager();

        $post->setActive(true);

        $em->persist($post);
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'The post : '.$post->getSlug().' has been activated.');

        return $this->redirect($_SERVER['HTTP_REFERER']);

    }

    public function deactivate(BlogPost $post, Request $request): Response
    {
        //TODO shouldn't be allowed to anyone 

        $em = $this->getDoctrine()->getEntityManager();

        $post->setActive(false);

        $em->persist($post);
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'The post : '.$post->getSlug().' has been deactivated.');

        return $this->redirect($request->headers->get('referer'));

    }

}
