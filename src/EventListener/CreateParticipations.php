<?php

namespace App\EventListener;

use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use App\Entity\Participation;
use App\Entity\Event;
use App\Entity\Player;

class CreateParticipations
{
    public function postPersist(LifecycleEventArgs $args)
    {
        $em = $args->getEntityManager();
        $event = $args->getObject();

        if ($event instanceof Event) {

            $players = $em->getRepository(Player::class)->findByTeam($event->getTeam());
            foreach ($players as $player) {
                $participation = new Participation();
                $player->addParticipation($participation);
                $event->addParticipation($participation);

                $em->persist($participation);
            }

            $em->flush();
        }

        return;
    }
}
