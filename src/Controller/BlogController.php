<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\BlogPost;
use App\Form\BlogPostType;
use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;


class BlogController extends AbstractController
{
    public function index($page): Response
    {	
        $nbPostPerPage = 12;			
        $em = $this->getDoctrine()->getManager();
        $posts = $em->getRepository(BlogPost::class)->findBy([], null, $nbPostPerPage, ($page-1)*$nbPostPerPage);
        
        $nbPost = $em->getRepository(BlogPost::class)->countAll();
            
        return $this->render('blog/index.html.twig', [
            'posts' => $posts,
            'page' => $page,
            'nbPost' => $nbPost,
            'nbPostPerPage' => $nbPostPerPage,
            'maxPage' => ceil($nbPost/$nbPostPerPage)
        ]);
    }

    public function view(BlogPost $post, Request $request): Response
    {
        $comment= new Comment();

        $form= $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $comment->setAuthor($this->getUser());
            $post->addComment($comment);

            $em = $this->getDoctrine()->getManager();
            $em->persist($comment);
            $em->persist($post);
            $em->flush();

            $request->getSession()->getFlashBag()->add('success', 'The comment : '.$post->getId().' has been aded.');
        }

        $comments= $post->getComments();

        return $this->render('blog/view.html.twig', [
            'post' => $post,
            'comments' => $comments,
            'form' => $form->createView()
        ]);

    }

    public function add(Request $request): Response
    {
        $post = new BlogPost();

        $form = $this->createForm(BlogPostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $post->setAuthor($this->getUser());

            $em = $this->getDoctrine()->getManager();            
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

        // TODO verify that the author is the authentified user 

        $form = $this->createForm(BlogPostType::class, $post);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $post->setEditDate(new \DateTime());
            $post->setActive(false);

            $em = $this->getDoctrine()->getManager();            
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

    public function commentDelete(Comment $comment, Request $request):Response
    {

        //TODO verify the user identity 

        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'The comment : '.$comment->getId().' has been removed.');

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
    
}
