<?php

namespace App\DataFixtures;

use App\DataFixtures\StartFixtures;
use App\DataFixtures\TeamFixtures;
use App\Entity\Event;
use App\Entity\EventTag;
use App\Entity\Feature;
use App\Entity\Location;
use App\Entity\PersonnalStat;
use App\Entity\StatTag;
use App\Entity\Team;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class EventFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        //language configuration
        $faker = Faker\Factory::create('en_US');

        //$tags = $this->getReference(TeamFixtures::TAGS_REF);
        $tags = $manager->getRepository(EventTag::class)->findAll();
        //$teams = $this->getReference(TeamFixtures::TEAMS_REF);
        $teams = $manager->getRepository(Team::class)->findAll();

        $statTags = $manager->getRepository(StatTag::class)->findAll();
        
        //small computation on external variable
        $nbTag = count($tags);
        $nbTeam = count($teams);

        //event creator
        $nbEvent = 30;
        $maxPlayer = 10;
        $events = range(0, $nbEvent);
        foreach ($events as $i => &$event) {
            $event = new Event();
            $date = $faker->dateTimeThisMonth($max = 'now', $timezone = null);
            $fin = new \DateTime($date->format('Y-m-d H:i:s'));
            $event->setStart($date);
            $event->setFinish($fin->add(new \DateInterval('PT2H')));
            $event->setInfo($faker->sentence(10, true));
            if ($faker->boolean) $event->setMaxPlayers($faker->numberBetween(0, $maxPlayer));
            $event->setName($faker->word());
            $event->setTag($tags[$faker->numberBetween(0, $nbTag-1)]);
            $event->setActive(true);

            $location = new Location();
            $location->setValue($faker->address());
            $location->setLat($faker->latitude(-90, 90));
            $location->setLng($faker->longitude(-180, 180));

            $event->setLocation($location);

            $teams[$faker->numberBetween(0, $nbTeam-1)]->addEvent($event);

            $manager->persist($event);
        }

        $manager->flush(); //to automatically create the participations ;-) 

        //add participation 
        foreach ($events as &$event) {
            foreach ($event->getParticipations() as &$participation) {
                if ($faker->boolean) $participation->setValue(true);
            }
        }

        //add stats 
        foreach ($events as &$event) {
            foreach ($event->getParticipationsIn() as &$participation) {
                //a distance 
                $stat = new PersonnalStat();
                $stat->setTag($statTags[0]);
                $stat->setParticipation($participation);
                $stat->setValue($faker->numberBetween(0,100));
                $manager->persist($stat);
                
                // a time 
                $stat = new PersonnalStat();
                $stat->setTag($statTags[1]);
                $stat->setParticipation($participation);
                $stat->setTimer(true);
                $stat->setValue($faker->numberBetween(0,30));

                //TODO save time and value in the same way (just change the display for times)
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            StartFixtures::class,
            TeamFixtures::class,
            UserFixtures::class
        ];
    }
}
