<?php

namespace App\DataFixtures;

use App\Entity\EventTag;
use App\Entity\FeatureTag;
use App\Entity\FieldTemplate;
use App\Entity\PlayerTag;
use App\Entity\Position;
use App\Entity\StatTag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use App\Entity\Team;
use App\Utils\ImageManager;
use Faker;

class TeamFixtures extends Fixture
{
    public const TEAMS_REF = 'teams';
    public const TAGS_REF = 'tags';

    private $imageManager;

    public function __construct(ImageManager $imageManager)
    {
        $this->imageManager = $imageManager;
    }

    public function load(ObjectManager $manager)
    {
        //language configuration
        $faker = Faker\Factory::create('en_US');

        //team creator 
        $nbTeam = 6;
        $teams = range(0, $nbTeam);
        foreach ($teams as &$team) {
            $team = new Team();
            $team->setName($faker->word);
            $image = $this->imageManager->manualImage('no-pic-team.jpg');
            $team->setImage($image);
            $team->setDescripsion($faker->text(200));
            $manager->persist($team);
        }

        // add some featuresTags
        $maxFTags = 4;
        foreach ($teams as &$team) {
            $nbFeatureTag = $faker->numberBetween(0, $maxFTags);
            for ($i = 0; $i < $nbFeatureTag; $i++) {
                $tag = new FeatureTag();
                $tag->setName($faker->word);
                $team->addFeature($tag);
                $manager->persist($tag);
            }
        }

        //create somme players tags
        $nbPlayerTag = 20;
        $playerTags = range(0, $nbPlayerTag);
        foreach ($playerTags as &$playerTag) {
            $playerTag = new PlayerTag();
            $playerTag->setColor(PlayerTag::COLORS[$faker->numberBetween(0, count(PlayerTag::COLORS)-1)]);
            $playerTag->setName($faker->word);
            $teams[$faker->numberBetween(0, $nbTeam - 1)]->addTag($playerTag);
            $manager->persist($playerTag);
        }

        //default resultTag
        $maxTag = 3;
        foreach ($teams as $team ) {
            $tags = range(0,$faker->numberBetween(0,$maxTag));
            foreach ($tags as &$tag) {
                $tag = new StatTag();
                $tag->setName($faker->word);
                if ($faker->boolean) $tag->setUnity($faker->randomLetter);
                $team->addStatTag($tag);

                $manager->persist($tag);
            }
        }

        //default EventTag creator
        foreach ($teams as $team) {
            $tag = new EventTag();
            $tag->setName($faker->word);
            $tag->setColor('info'); //cannot get a radom color
            $team->addEventTag($tag);

            $manager->persist($tag);
        }

        //template creator
        //TODO link it to team
        $nbTemplate = 3;
        $templates = range(0, $nbTemplate);
        foreach ($templates as $i => &$template) {
            $template = new FieldTemplate();
            $template->setName($faker->word);
            $image = $this->imageManager->manualImage('empty_field.jpg');
            $template->setImage($image);
            $nbPosition = $faker->numberBetween(0, 10);
            for ($p = 0; $p < $nbPosition; $p++) {
                $horizontal = $faker->numberBetween(0, 100);
                $vertical = $faker->numberBetween(0, 100);
                $position = new Position($horizontal, $vertical);
                $position->setName($faker->word);
                $template->addPosition($position);
            }

            $manager->persist($template);
        }

        $manager->flush();

        //$this->addReference(self::TEAMS_REF, $teams);
        //$this->addReference(self::TAGS_REF, $tags);
    }
}
