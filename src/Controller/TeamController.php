<?php

namespace App\Controller;

use App\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Player;
use App\Entity\User;

class TeamController extends AbstractController
{
    public function playerIndex(Team $team): Response
    {
        $em = $this->getDoctrine()->getManager();
        $players = $em->getRepository(Player::class)->findByTeam($team);

        return $this->render('team/playerIndex.html.twig', [
            'team' => $team,
            'players'=>$players
        ]);
    }
}
