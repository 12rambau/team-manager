<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use App\Entity\User;
use App\Entity\BlogPost;
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
        $faker = Faker\Factory::create('en');

        //user creator
        $user = new User();
        $user->setLastName('root');
        $user->setFirstName('root');
        $user->setUserName('root');
        $user->setPassword($this->passwordEncoder->encodePassword($user,'root'));
        $user->setEmail('root@root.com');

        $manager->persist($user);

        //post creator
        for ($i=0; $i < 3; $i++)
        {
            $post = new BlogPost();
            $post->setTitle($faker->words(4, true));
            $post->setShort($faker->sentence(30, true));
            $post->setContent($faker->text(500));
            //$post->setActive(true);
            $post->setAuthor($user);

            $manager->persist($post);
        }

        $manager->flush();
    }
}
