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
        $em = $this->getDoctrine()->getEntityManager();
        $posts = $em->getRepository(BlogPost::class)->findSome(($page-1)*10, 10);
            
        return $this->render('blog/index.html.twig', [
            'posts' => $posts
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

            $em = $this->getDoctrine()->getEntityManager();
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

        $post->setActive(!$post->getActive());

        $em->persist($post);
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'The post : '.$post->getSlug().' has change visibility.');

        return $this->redirect($_SERVER['HTTP_REFERER']);

    }

    public function adminIndex($page): Response
    {
        $em = $this->getDoctrine()->getEntityManager();
        $posts = $em->getRepository(BlogPost::class)->findSome(($page-1)*30, 30);
            
        return $this->render('blog/adminIndex.html.twig', [
            'posts' => $posts
        ]);
    }

    public function adminView(BlogPost $post): Response
    {
        return $this->render('blog/adminView.html.twig', array(
            'post' => $post
        ));

    }

    public function commentAdmin($page): Response
    {
        $em = $this->getDoctrine()->getEntityManager();
        $comments = $em->getRepository(Comment::class)->findSome(($page-1)*30,30);

        return $this->render('comment/adminIndex.html.twig', [
            'comments' => $comments
        ]);
    }

    public function commentDelete(Comment $comment, Request $request):Response
    {
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($comment);
        $em->flush();

        $request->getSession()->getFlashBag()->add('success', 'The comment : '.$comment->getId().' has been removed.');

        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

}
