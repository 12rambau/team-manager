<?php

namespace App\DataFixtures;

use App\DataFixtures\StartFixtures;
use App\DataFixtures\TeamFixtures;
use App\Entity\Player;
use App\Entity\Team;
use App\Entity\User;
use App\Utils\ImageManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public const USERS_REF = 'users';

    private $passwordEncoder;
    private $imageManager;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, ImageManager $imageManager)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->imageManager = $imageManager;
    }

    public function load(ObjectManager $manager)
    {

        //language configuration
        $faker = Faker\Factory::create('en_US');

        $root = $this->getReference(StartFixtures::ROOT_REF);

        //$teams = $this->getReference(TeamFixtures::TEAMS_REF);
        $teams = $manager->getRepository(Team::class)->findAll();

        $nbUser = 10;
        $users = range(0, $nbUser);
        foreach ($users as $i => &$user) {
            $user = new User();
            $user->setGender($faker->boolean());
            $gender = $user->getGender() ? 'male' : 'female';
            $user->setLastName($faker->lastName());
            $user->setFirstName($faker->firstName($gender));
            $user->setUserName($user->getfirstName() . $user->getLastName());
            $user->setPassword($this->passwordEncoder->encodePassword($user, 'userdemo' . $i));
            $user->setEmail($user->getfirstName() . "." . $user->getLastName() . '@team.com');
            $user->setBirthDate($faker->dateTimeThisCentury());
            $image = $this->imageManager->manualImage('no-profile-pic-' . $gender . '.jpg');
            $user->setProfilePic($image);
            $user->setPhoneNumber($faker->e164PhoneNumber());
            $manager->persist($user);
        }

        //create the players 
        foreach ($users as  &$user) {
            foreach ($teams as &$team) {
                if ($faker->boolean) {
                    $player = new Player();
                    $team->addPlayer($player);
                    $user->addPlayer($player);
                    foreach ($team->getTags() as &$tag) {
                        if ($faker->boolean) $player->addTag($tag);
                    }
                    $manager->persist($player);
                }
            }
        }

        //set some players for the root user 
        foreach ($teams as &$team) {
            $player = new Player();
            $player->setTeam($team);
            $tags = $player->getTeam()->getTags();
            foreach ($tags as &$tag) {
                if ($faker->boolean) $player->addTag($tag);
            }
            $player->setUser($root);
            $manager->persist($player);
        }

        $manager->flush();

        //$this->addReference(self::USERS_REF, $users);
    }

    public function getDependencies()
    {
        return [
            StartFixtures::class,
            TeamFixtures::class
        ];
    }
}
