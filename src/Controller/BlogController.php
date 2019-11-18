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

    public function edit(BlogPost $post=null, Request $request)
    {
        $action = "edit";
        //create the post
        if (!$post){
            $post = new BlogPost(); 
            $action = "add";
        //create the post
        } elseif (
                $this->getUser() != $post->getAuthor() 
                && 
                !$this->container->get('security.authorization_checker')->isGranted('ROLE_ADMIN')
            ) 
        {
            throw new AccessDeniedException('Not your Blog Post');
        }

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
            'post' => $post,
            'action' => $action
        ]);

    }

    public function commentDelete(Comment $comment, Request $request):Response
    {

        if ($this->getUser() != $comment->getAuthor()) 
            throw new AccessDeniedException('Not your Comment');

        $em = $this->getDoctrine()->getManager();
        $em->remove($comment);
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'The comment : '.$comment->getId().' has been removed.');

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
    
}
