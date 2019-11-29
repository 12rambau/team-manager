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
use App\Entity\Comment;
use App\Entity\FieldTemplate;
use App\Entity\Image;
use App\Entity\Position;
use App\Entity\StatTag;
use App\Entity\Partner;
use App\Entity\Player;
use App\Entity\Team;
use App\Entity\PlayerTag;
use App\Entity\Social;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
        $root->setBirthDate($faker->dateTimeThisCentury());
        $image = AppFixtures::manualImage('no-profile-pic-male.jpg', $rootDir);
        $root->setProfilePic($image);
        $root->setRoles(["ROLE_ADMIN"]);

        $manager->persist($root);

        $nbUser = 10;
        $users = range(0,$nbUser);
        foreach ($users as $i => &$user)
        {
            $user = new User();
            $user->setGender($faker->boolean());
            $gender = $user->getGender() ? 'male':'female';
            $user->setLastName($faker->lastName());
            $user->setFirstName($faker->firstName($gender));
            $user->setUserName($user->getfirstName().$user->getLastName());
            $user->setPassword($this->passwordEncoder->encodePassword($user,'userdemo'.$i));
            $user->setEmail($user->getfirstName().".".$user->getLastName().'@team.com');
            $user->setBirthDate($faker->dateTimeThisCentury());
            $image = AppFixtures::manualImage('no-profile-pic-'.$gender.'.jpg', $rootDir);
            $user->setProfilePic($image);
            $user->setPhoneNumber($faker->e164PhoneNumber());
            $manager->persist($user);
        }

        $manager->flush();

        //post creator
        $nbPost = 20;
        $posts = range(0,$nbPost);
        foreach ($posts as &$post)
        {
            $post = new BlogPost();
            $post->setTitle($faker->words(4, true));
            $post->setShort($faker->sentence(30, true));
            $post->setContent($faker->paragraphs(5,true));
            $post->setActive(true);
            $post->setAuthor($users[$faker->numberBetween(0, ($nbUser-1))]);

            $manager->persist($post);
        }

        //commments creator
        $nbComment = 40;
        $comments = range(0,$nbComment);
        foreach ($comments as &$comment)
        {
            $comment = new Comment();
            $comment->setContent($faker->text(50));
            $comment->setAuthor($users[$faker->numberBetween(0, $nbUser-1)]);
            $comment->setPost($posts[$faker->numberBetween(0, $nbPost-1)]);

            $manager->persist($comment);
        }

        //default EventTag creator
        $tag = range(0,3);
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
        $nbEvent = 30;
        $events = range(0,$nbEvent);
        foreach ($events as $i => &$event)
        {
            $event = new Event();

            $date = $faker->dateTimeThisMonth($max = 'now', $timezone = null);
            $fin = new \DateTime($date->format('Y-m-d H:i:s'));
            $event->setStart($date);
            $event->setFinish($fin->add(new \DateInterval('PT2H')));
            $event->setInfo($faker->sentence(10, true));
            if ($faker->boolean) $event->setMaxPlayers($faker->numberBetween(0, $nbUser));
            $event->setName($faker->word());
            $event->setTag($tag[$faker->numberBetween(0, 2)]);
            $event->setActive(true);

            $location = new Location();
            $location->setValue($faker->address());
            $location->setLat($faker->latitude($min = -90, $max = 90));
            $location->setLng($faker->longitude($min = -180, $max = 180));

            $event->setLocation($location);

            $manager->persist($event);

        }

        //message creator
        $nbMessage = 50;
        $messages = range(0,$nbMessage);
        foreach ($messages as $i => &$message)
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
        $nbTemplate = 3;
        $templates = range(0,$nbTemplate);
        foreach ($templates as $i => &$template)
        {
            $template = new FieldTemplate();
            $template->setName('template'.$i);
            $image = AppFixtures::manualImage('empty_field.jpg', $rootDir);
            $template->setImage($image);
            $nbPosition = $faker->numberBetween(0, 10);
            for ($p=0; $p < $nbPosition; $p++)
            {
                $horizontal = $faker->numberBetween(0,100);
                $vertical = $faker->numberBetween(0,100);
                $position = new Position($horizontal,$vertical);
                $position->setName($faker->word);
                $template->addPosition($position);

            }

            $manager->persist($templates[$i]);
        }

        //default resultTag
        $statlTags = array(2);

        $statlTags[0] = new StatTag();
        $statlTags[0]->setName('distance');
        $statlTags[0]->setUnity("m");
        $manager->persist($statlTags[0]);

        $statlTags[1] = new StatTag();
        $statlTags[1]->setName('chrono');
        $statlTags[1]->setUnity("");
        $manager->persist($statlTags[1]);


        //2 default partners
        $nbPartner = 2;
        $partners = array($nbPartner);

        $partners[0] = new Partner();
        $partners[0]->setName("Olympics");
        $partners[0]->setWebsite('https://www.olympic.org');
        $image = AppFixtures::manualImage('olympic.png', $rootDir);
        $partners[0]->setImage($image);
        $manager->persist($partners[0]);

        $partners[1] = new Partner();
        $partners[1]->setName("Red Bull");
        $partners[1]->setWebsite('https://www.redbull.com');
        $image = AppFixtures::manualImage('red_bull.png', $rootDir);
        $partners[1]->setImage($image);
        $manager->persist($partners[1]);


        //team creator 
        $nbTeam = 6;
        $teams = range(0,$nbTeam);
        foreach ($teams as $key => &$team) {
            $team = new Team();
            $team->setName('team-'.$key);
            $image = AppFixtures::manualImage('no-pic-team.jpg', $rootDir);
            $team->setImage($image);
            $team->setDescripsion($faker->text(200));
            $manager->persist($team);
        }

        //create somme players tags
        $nbPlayerTag = 20;
        $playerTags = range(0, $nbPlayerTag);
        foreach($playerTags as &$playerTag){
            $playerTag = new PlayerTag();
            //TODO set the color of the tag 
            $playerTag->setName($faker->word);
            $playerTag->setTeam($teams[$faker->numberBetween(0,$nbTeam-1)]);
            $manager->persist($playerTag);
        }

        //create the players 
        foreach ($users as  &$user) {
            foreach ($teams as &$team) {
                if ($faker->boolean){
                    $player = new Player();
                    $player->setTeam($team);
                    $user->addPlayer($player);
                    $player->setUser($user);
                    $tags = $player->getTeam()->getTags();
                    foreach($tags as &$tag){
                        if ($faker->boolean) $player->addTag($tag);
                    }
                }
            }
        }

        //set som players for the root user 
        foreach($teams as &$team){
            $player = new Player();
            $player->setTeam($team);
            $tags = $player->getTeam()->getTags();
            foreach($tags as &$tag){
                if ($faker->boolean) $player->addTag($tag);
            }
            $player->setUser($root);
            $manager->persist($player);
        }

        //social media 
        $socials[0] = new Social();
        $socials[0]->setName('youtube');
        $socials[0]->setUrl('https://www.youtube.com/channel/UC9UGYdMSKasjY0mhU1lG4lQ');
        $socials[0]->setIcon('youtube');
        $socials[0]->setColor('#c4302b');
        $manager->persist($socials[0]);

        $socials[1] = new Social();
        $socials[1]->setName('instagram');
        $socials[1]->setUrl('https://www.instagram.com/nitrocircus/');
        $socials[1]->setIcon('instagram');
        $socials[1]->setColor('#C13584');
        $manager->persist($socials[1]);

        $socials[2] = new Social();
        $socials[2]->setName('strava');
        $socials[2]->setUrl('https://blog.strava.com');
        $socials[2]->setIcon('strava');
        $socials[2]->setColor('#fc4c02');
        $manager->persist($socials[2]);

        $socials[3] = new Social();
        $socials[3]->setName('twitter');
        $socials[3]->setUrl('https://twitter.com/NitroCircus');
        $socials[3]->setIcon('twitter');
        $socials[3]->setColor('#1da1f2');
        $manager->persist($socials[3]);

        $socials[4] = new Social();
        $socials[4]->setName('facebook');
        $socials[4]->setUrl('https://www.facebook.com/NitroCircus/');
        $socials[4]->setIcon('facebook-f');
        $socials[4]->setColor('#3B5998');
        $manager->persist($socials[4]);


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
