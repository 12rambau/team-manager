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
use App\Entity\Comment;
use App\Entity\FieldTemplate;
use App\Entity\Image;
use App\Entity\Position;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class AppFixtures extends Fixture
{
    private $passwordEncoder;
    private $container;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, ContainerInterface $container)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->container = $container;
    }
    public function load(ObjectManager $manager)
    {

        //language configuration
        $faker = Faker\Factory::create('en_US');

        //get the root Directory
        $rootDir = $this->container->get('kernel')->getRootDir().'/..';
    
        //empty the upload dir
        $dirPath = $rootDir.'/public/image/upload/';

        foreach(new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dirPath, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $path)
            $path->isDir() && !$path->isLink() ? rmdir($path->getPathname()) : unlink($path->getPathname());

        //create the contact info 
        $contact = new Location();
        $contact->setValue($faker->address());
        $contact->setLat($faker->latitude($min = -90, $max = 90));
        $contact->setLng($faker->longitude($min = -180, $max = 180));
        $contact->setTag('contact');

        $manager->persist($contact);

        //users creator (me)
        $root = new User();
        $root->setLastName('root');
        $root->setFirstName('root');
        $root->setUserName('root');
        $root->setPassword($this->passwordEncoder->encodePassword($root,'root'));
        $root->setEmail('root@root.com');
        $root->setGender(true);
        $root->setPhoneNumber($faker->e164PhoneNumber());
        $image = AppFixtures::manualImage('no-profile-pic-male.jpg', $rootDir);
        $root->setProfilePic($image);
        $root->setRoles(["ROLE_ADMIN"]);

        $manager->persist($root);

        $nbUser = 50;
        $users = array($nbUser);
        for ($i=0; $i < $nbUser; $i++)
        {
            $users[$i] = new User();
            $users[$i]->setGender($faker->boolean());
            $gender = $users[$i]->getGender() ? 'male':'female';
            $users[$i]->setLastName($faker->lastName());
            $users[$i]->setFirstName($faker->firstName($gender));
            $users[$i]->setUserName($users[$i]->getfirstName().$users[$i]->getLastName());
            $users[$i]->setPassword($this->passwordEncoder->encodePassword($users[$i],'userdemo'.$i));
            $users[$i]->setEmail($users[$i]->getfirstName().".".$users[$i]->getLastName().'@team.com');
            $image = AppFixtures::manualImage('no-profile-pic-'.$gender.'.jpg', $rootDir);
            $users[$i]->setProfilePic($image);
            $users[$i]->setPhoneNumber($faker->e164PhoneNumber());
            $manager->persist($users[$i]);
        }

        $manager->flush();

        //post creator
        $nbPost = 70;
        $posts = array($nbPost);
        for ($i=0; $i < $nbPost; $i++)
        {
            $posts[$i] = new BlogPost();
            $posts[$i]->setTitle($faker->words(4, true));
            $posts[$i]->setShort($faker->sentence(30, true));
            $posts[$i]->setContent($faker->text(500));
            $posts[$i]->setActive(true);
            $posts[$i]->setAuthor($users[$faker->numberBetween(0, ($nbUser-1))]);

            $manager->persist($posts[$i]);
        }

        //commments creator
        $nbComment = 600;
        $comments = array($nbComment);
        for ($i=0; $i < $nbComment; $i++)
        {
            $comments[$i] = new Comment();
            $comments[$i]->setContent($faker->text(50));
            $comments[$i]->setAuthor($users[$faker->numberBetween(0, $nbUser-1)]);
            $comments[$i]->setPost($posts[$faker->numberBetween(0, $nbPost-1)]);

            $manager->persist($comments[$i]);
        }

        //default EventTag creator
        $tags = array(3);
        $tag[1] = new EventTag();
        $tag[1]->setName('trainning');
        $tag[1]->setColor('success');
        $manager->persist($tag[1]);

        $tag[2] = new EventTag();
        $tag[2]->setName('Race');
        $tag[2]->setColor('primary');
        $manager->persist($tag[2]);

        $tag[0] = new EventTag();
        $tag[0]->setName('other');
        $tag[0]->setColor('warning');
        $manager->persist($tag[0]);

        //event creator
        $nbEvent = 100;
        $events = array($nbEvent);
        for ($i=0; $i< $nbEvent; $i++)
        {
            $events[$i] = new Event();

            $date = $faker->dateTimeThisMonth($max = 'now', $timezone = null);
            $fin = new \DateTime($date->format('Y-m-d H:i:s'));
            $events[$i]->setStart($date);
            $events[$i]->setFinish($fin->add(new \DateInterval('PT2H')));
            $events[$i]->setInfo($faker->sentence(10, true));
            if ($i%2 == 0) $events[$i]->setMaxPlayers($faker->numberBetween(0, $nbUser));
            $events[$i]->setName($faker->word());
            $events[$i]->setTag($tag[$faker->numberBetween(0, 2)]);
            $events[$i]->setActive(true);

            $location = new Location();
            $location->setValue($faker->address());
            $location->setLat($faker->latitude($min = -90, $max = 90));
            $location->setLng($faker->longitude($min = -180, $max = 180));

            $events[$i]->setLocation($location);

            $manager->persist($events[$i]);

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
            $message->setAuthor($users[$faker->numberBetween(0, ($nbUser-1))]);

            $manager->persist($message);
        }

        //template creator
        $nbTemplate = 1;
        $templates = array($nbTemplate);
        for ($i=0; $i < $nbTemplate; $i++)
        {
            $templates[$i] = new FieldTemplate();
            $templates[$i]->setName('template'.$i);
            $image = AppFixtures::manualImage('empty_field.jpg', $rootDir);
            $templates[$i]->setImage($image);
            $nbPosition = $faker->numberBetween(0, 10);
            for ($pstn=0; $pstn < $nbPosition; $pstn++)
            {
                $horizontal = $faker->numberBetween(0,100);
                $vertical = $faker->numberBetween(0,100);
                $position = new Position($horizontal,$vertical);
                $position->setName($faker->word);
                $templates[$i]->addPosition($position);

            }

            $manager->persist($templates[$i]);
        }

        $manager->flush();
    }

    public function ManualImage(string $imageName, string $rootDir):Image
    {
        $image = new Image();

        $image->setUpdatedAt(new \DateTime());

        $sourcePath = $rootDir.'/public/image/default/'.$imageName;

        $name = str_replace('.', '', uniqid('', true));
        if ($extension = pathinfo($sourcePath, PATHINFO_EXTENSION))
            $name = sprintf('%s.%s', $name, $extension);

        $copyPath = $rootDir.'/public/image/upload/'.$name;

        copy($sourcePath, $copyPath);
        $image->setFileName($name);

        return $image;
    }
}
