<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;

class TeamController extends AbstractController
{
    public function playerIndex(): Response
    {
        $em = $this->getDoctrine()->getEntityManager();
        $players = $em->getRepository(User::class)->findAll();

        return $this->render('team/playerIndex.html.twig', [
            'players'=>$players
        ]);
    }
}
