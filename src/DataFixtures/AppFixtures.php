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

        //user creator
        $user = new User();
        $user->setLastName('root');
        $user->setFirstName('root');
        $user->setUserName('root');
        $user->setPassword($this->passwordEncoder->encodePassword($user,'root'));
        $user->setEmail('root@root.com');

        $manager->persist($user);
        //$manager->flush();

        //post creator
        for ($i=0; $i < 50; $i++)
        {
            $post = new BlogPost();
            $post->setTitle($faker->words(4, true));
            $post->setShort($faker->sentence(30, true));
            $post->setContent($faker->text(500));
            $post->setActive(true);
            $post->setAuthor($user);

            $manager->persist($post);
        }

        //default EventTag creator
        $tag = new EventTag();
        $tag->setName('trainning');
        $manager->persist($tag);

        $tag = new EventTag();
        $tag->setName('Race');
        $manager->persist($tag);

        $tag = new EventTag();
        $tag->setName('other');
        $manager->persist($tag);

        //event creator
        $events = array(30);
        for ($i=0; $i< 30; $i++)
        {
            $events[$i] = new Event();

            $date = $faker->dateTimeThisMonth($max = 'now', $timezone = null);
            $fin = new \DateTime($date->format('Y-m-d H:i:s'));
            $events[$i]->setStart($date);
            $events[$i]->setFinish($fin->add(new \DateInterval('PT2H')));
            $events[$i]->setInfo($faker->sentence(10, true));
            $events[$i]->setMaxPlayers(10);
            $events[$i]->setName($faker->word());
            $events[$i]->setTag($tag);
            $events[$i]->setActive(true);

            $location = new Location();
            $location->setFullAdr($faker->address());
            $location->setLat($faker->latitude($min = -90, $max = 90));
            $location->setLng($faker->longitude($min = -180, $max = 180));

            $events[$i]->setLocation($location);

            $manager->persist($events[$i]);

        }

        //participation // TODO shouldn't be necessary
        $nbEvents = count($events);
        for ($i=0; $i < $nbEvents; $i++)
        {
            $participation = new Participation();
            $participation->setUser($user);
            $participation->setEvent($events[$i]);

            $manager->persist($participation);
        }

        //message creator
        for ($i = 0; $i < 50; $i++)
        {
            $message = new ChatMessage();

            $date = new \DateTime();
            $date->add(new \DateInterval('PT'.$i.'S'));
            $message->setDate($date);
            $message->setContent($faker->text(100));
            $message->setAuthor($user);

            $manager->persist($message);
        }

        $manager->flush();
    }
}
