<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\BlogPost;

class BlogController extends AbstractController
{
    public function index($page): Response
    {		
		//on recupere la liste des news du sport, on en affiche 10 par page		
        $em = $this->getDoctrine()->getEntityManager();
        $listPosts = $em->getRepository(BlogPost::class)->findTen(($page-1)*10);
            
        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'listPosts' => $listPosts
        ]);
    }


}
