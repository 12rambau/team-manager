<?php

namespace App\DataFixtures;

use App\Entity\BlogPost;
use App\Entity\ChatMessage;
use App\Entity\Comment;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;

class BlogFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        //language configuration
        $faker = Faker\Factory::create('en_US');

        $users = $manager->getRepository(User::class)->findAll();
        //$users = $this->getReference(UserFixtures::USERS_REF);

        $nbUser = count($users);

        //post creator
        $nbPost = 20;
        $posts = range(0, $nbPost);
        foreach ($posts as &$post) {
            $post = new BlogPost();
            $post->setTitle($faker->words(4, true));
            $post->setShort($faker->sentence(30, true));
            $post->setContent($faker->paragraphs(5, true));
            $post->setActive(true);
            $post->setAuthor($users[$faker->numberBetween(0, ($nbUser - 1))]);

            $manager->persist($post);
        }

        //commments creator
        $nbComment = 40;
        $comments = range(0, $nbComment);
        foreach ($comments as &$comment) {
            $comment = new Comment();
            $comment->setContent($faker->text(50));
            $comment->setAuthor($users[$faker->numberBetween(0, $nbUser - 1)]);
            $comment->setPost($posts[$faker->numberBetween(0, $nbPost - 1)]);

            $manager->persist($comment);
        }

        //message creator
        $nbMessage = 50;
        $messages = range(0, $nbMessage);
        foreach ($messages as $i => &$message) {
            $message = new ChatMessage();

            $date = new \DateTime();
            $date->add(new \DateInterval('PT' . $i . 'S'));
            $message->setDate($date);
            $message->setContent($faker->text(100));
            $message->setAuthor($users[$faker->numberBetween(0, ($nbUser - 1))]);

            $manager->persist($message);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return[
            UserFixtures::class
        ];
    }
}
