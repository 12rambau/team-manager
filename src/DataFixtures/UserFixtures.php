<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\User;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        //language configuration
        $faker = Faker\Factory::create('fr_FR');

        //10 users creation
/*         for($i=0; $i < 10; $i++)
        {
            $user = new User();
            $user->setLastName($faker->lastName);
            $user->setFirstName($faker->firstName);
            $user->setUserName($user->getLastName().$user->getFirstName());
            $user->setPassword($this->passwordEncoder->encodePassword($user,'userdemo'));

            $manager->persist($user);
        } */
    }
}
