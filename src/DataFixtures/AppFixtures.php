<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use App\Entity\User;
use App\Entity\BlogPost;
use App\Entity\Event;
use App\Entity\ChatMessage;
use App\Entity\EventTag;
use App\Entity\Location;
use App\Entity\Participation;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {

        //language configuration
        $faker = Faker\Factory::create('en_US');

        //users creator
        $root = new User();
        $root->setLastName('root');
        $root->setFirstName('root');
        $root->setUserName('root');
        $root->setPassword($this->passwordEncoder->encodePassword($root,'root'));
        $root->setEmail('root@root.com');

        $manager->persist($root);

        $nbUser = 15;
        $users = array($nbUser);
        for ($i=0; $i < $nbUser; $i++)
        {
            $users[$i] = new User();
            $users[$i]->setLastName($faker->lastName());
            $users[$i]->setFirstName($faker->firstName());
            $users[$i]->setUserName($users[$i]->getfirstName().$users[$i]->getLastName());
            $users[$i]->setPassword($this->passwordEncoder->encodePassword($users[$i],'userdemo'.$i));
            $users[$i]->setEmail($users[$i]->getfirstName().".".$users[$i]->getLastName().'@team.com');

            $manager->persist($users[$i]);
        }

        //post creator
        $nbPost = 50;
        $posts = array($nbPost);
        for ($i=0; $i < $nbPost; $i++)
        {
            $posts[$i] = new BlogPost();
            $posts[$i]->setTitle($faker->words(4, true));
            $posts[$i]->setShort($faker->sentence(30, true));
            $posts[$i]->setContent($faker->text(500));
            $posts[$i]->setActive(true);
            $j = $faker->numberBetween(0, ($nbUser-1));
            $posts[$i]->setAuthor($users[$j]);

            $manager->persist($posts[$i]);
        }

        //default EventTag creator
        $tags = array(3);
        $tag[1] = new EventTag();
        $tag[1]->setName('trainning');
        $manager->persist($tag[1]);

        $tag[2] = new EventTag();
        $tag[2]->setName('Race');
        $manager->persist($tag[2]);

        $tag[0] = new EventTag();
        $tag[0]->setName('other');
        $manager->persist($tag[0]);

        //event creator
        $nbEvent = 30;
        $events = array($nbEvent);
        for ($i=0; $i< $nbEvent; $i++)
        {
            $events[$i] = new Event();

            $date = $faker->dateTimeThisMonth($max = 'now', $timezone = null);
            $fin = new \DateTime($date->format('Y-m-d H:i:s'));
            $events[$i]->setStart($date);
            $events[$i]->setFinish($fin->add(new \DateInterval('PT2H')));
            $events[$i]->setInfo($faker->sentence(10, true));
            $events[$i]->setMaxPlayers(10);
            $events[$i]->setName($faker->word());
            $j = $faker->numberBetween(0, 2);
            $events[$i]->setTag($tag[$j]);
            $events[$i]->setActive(true);

            $location = new Location();
            $location->setFullAdr($faker->address());
            $location->setLat($faker->latitude($min = -90, $max = 90));
            $location->setLng($faker->longitude($min = -180, $max = 180));

            $events[$i]->setLocation($location);

            $manager->persist($events[$i]);

        }

        //participation // TODO shouldn't be necessary
        for ($i=0; $i < $nbEvent; $i++)
        {
            for ($j=0; $j < $nbUser; $j++)
            {
                $participation = new Participation();
                $participation->setUser($users[$j]);
                $participation->setEvent($events[$i]);

                $manager->persist($participation);
            }

            $participation = new Participation();
            $participation->setUser($root);
            $participation->setEvent($events[$i]);

            $manager->persist($participation);
        }

        //message creator
        $nbMessage = 100;
        for ($i = 0; $i < $nbMessage; $i++)
        {
            $message = new ChatMessage();

            $date = new \DateTime();
            $date->add(new \DateInterval('PT'.$i.'S'));
            $message->setDate($date);
            $message->setContent($faker->text(100));
            $j = $faker->numberBetween(0, ($nbUser-1));
            $message->setAuthor($users[$j]);

            $manager->persist($message);
        }

        $manager->flush();
    }
}
