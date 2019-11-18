<?php

namespace App\Controller;

use App\Entity\BlogPost;
use App\Entity\Comment;
use App\Form\CommentType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Vich\UploaderBundle\Templating\Helper\UploaderHelper;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;

class CommentController extends AbstractController
{
    public function add(BlogPost $post, Request $request, UploaderHelper $helper, CacheManager $cm): JsonResponse
    {

        //TODO check if the request is a null comment and print the error 
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $post->addComment($comment);
            $em->persist($post);
            $em->flush();
        }

        //TODO use the symfony serializer

        $data = array();
        $data['content'] = $comment->getContent();
        $data['username'] = $comment->getAuthor()->getUsername();
        $data['publishDate'] = date_format($comment->getPublishDate(), 'd/m h:i');
        $data['profilePicUrl'] = $cm->getBrowserPath($helper->asset($comment->getAuthor()->getProfilePic(), 'imageFile'), 'userNavbar');


        return new JsonResponse($data);
    }

    public function delete(Comment $comment): JsonResponse
    {

        //TODO check this function 


        //check the user 
        if ($this->getUser() != $comment->getAuthor())
            throw new AccessDeniedException('Not your Comment');

        //delete the comment 
        $em = $this->getDoctrine()->getmanager();
        $em->remove($comment);
        $em->flush();

        $data['status'] = "done";

        return new JsonResponse($data);
    }
}
