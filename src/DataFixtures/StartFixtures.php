<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Faker;
use App\Entity\Location;
use App\Entity\User;
use App\Utils\ImageManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class StartFixtures extends Fixture
{
    public const FAKER_REF = 'faker';
    public const ROOT_DIR_REF = 'rootDir';
    public const ROOT_REF = 'root';

    private $passwordEncoder;
    private $imageManager;
    private $projectDir;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder, ImageManager $imageManager, string $projectDir)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->imageManager = $imageManager;
        $this->projectDir = $projectDir;
    }

    public function load(ObjectManager $manager)
    {
        //language configuration
        $faker = Faker\Factory::create('en_US');

        //empty the upload dir
        $dirPath = $this->projectDir . '/public/image/upload/';

        foreach (new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dirPath, \FilesystemIterator::SKIP_DOTS), \RecursiveIteratorIterator::CHILD_FIRST) as $path)
            $path->isDir() && !$path->isLink() ? rmdir($path->getPathname()) : unlink($path->getPathname());

        //create the contact info 
        $contact = new Location();
        $contact->setValue($faker->address());
        $contact->setLat($faker->latitude(-90, 90));
        $contact->setLng($faker->longitude(-180, 180)); 
        $contact->setTag('contact');

        $manager->persist($contact);

        //users creator (me)
        $root = new User();
        $root->setLastName('root');
        $root->setFirstName('root');
        $root->setUserName('root');
        $root->setPassword($this->passwordEncoder->encodePassword($root, 'root'));
        $root->setEmail('root@root.com');
        $root->setGender(true);
        $root->setPhoneNumber($faker->e164PhoneNumber());
        $root->setBirthDate($faker->dateTimeThisCentury());
        $image = $this->imageManager->manualImage('no-profile-pic-male.jpg');
        $root->setProfilePic($image);
        $root->setRoles(["ROLE_ADMIN"]);

        $manager->persist($root);

        $manager->flush();

        $this->addReference(self::ROOT_REF, $root);
    }
}
