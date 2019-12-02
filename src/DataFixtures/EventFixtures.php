<?php

namespace App\DataFixtures;

use App\DataFixtures\StartFixtures;
use App\DataFixtures\TeamFixtures;
use App\Entity\Event;
use App\Entity\EventTag;
use App\Entity\Feature;
use App\Entity\Location;
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
        
        //small computation on external variable
        $nbTag = count($tags);

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

            $manager->persist($event);
        }

        // add features 
        //link to events ? 
        $maxFeatures = 5;
        foreach ($teams as &$team) {
            $nb = count($team->getFeatures());
            if ($nb) {
                foreach ($team->getPlayers() as &$player) {
                    $nbFeature = $faker->numberBetween(0, $maxFeatures);
                    for ($i = 0; $i < $nbFeature; $i++) {
                        $feature = new Feature();
                        $tag = $team->getFeatures()->get($faker->numberBetween(0, $nb - 1));
                        $tag->addFeature($feature);
                        $feature->setValue($faker->word);
                        $player->addFeature($feature);
                        $manager->persist($feature);
                    }
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            StartFixtures::class,
            TeamFixtures::class
        ];
    }
}
