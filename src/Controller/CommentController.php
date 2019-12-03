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
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpFoundation\Response;

class CommentController extends AbstractController
{
    public function add(BlogPost $post, Request $request, UploaderHelper $helper, CacheManager $cm): JsonResponse
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        $data = array();

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setAuthor($this->getUser());
            $em = $this->getDoctrine()->getManager();
            $post->addComment($comment);
            $em->persist($post);
            $em->flush();

            //TODO use the symfony serializer
            $data['content'] = $comment->getContent();
            $data['username'] = $comment->getAuthor()->getUsername();
            $data['publishDate'] = date_format($comment->getPublishDate(), 'd/m h:i');
            $data['profilePicUrl'] = $cm->getBrowserPath($helper->asset($comment->getAuthor()->getProfilePic(), 'imageFile'), 'userNavbar');
            
        } else {
            //TODO return the real error to display it
            return $this->json($form, Response::HTTP_BAD_REQUEST);
        }

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


    public function edit(Comment $comment)
    {
        //do an edit function
        return -1;
    }
}
