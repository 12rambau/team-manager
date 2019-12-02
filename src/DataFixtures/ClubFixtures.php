<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\DataFixtures\StartFixtures;
use App\Entity\Partner;
use App\Entity\Social;
use App\Utils\ImageManager;

class ClubFixtures extends Fixture
{
    private $imageManager;

    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    public function load(ObjectManager $manager)
    {
        //2 default partners
        $nbPartner = 2;
        $partners = array($nbPartner);

        $partners[0] = new Partner();
        $partners[0]->setName("Olympics");
        $partners[0]->setWebsite('https://www.olympic.org');
        $image = $this->imageManager->manualImage('olympic.png');
        $partners[0]->setImage($image);
        $manager->persist($partners[0]);

        $partners[1] = new Partner();
        $partners[1]->setName("Red Bull");
        $partners[1]->setWebsite('https://www.redbull.com');
        $image = $this->imageManager->manualImage('red_bull.png');
        $partners[1]->setImage($image);
        $manager->persist($partners[1]);

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
}