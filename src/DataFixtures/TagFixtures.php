<?php

namespace App\DataFixtures;

use App\DataFixtures\Config\FixturesConfig;
use App\Entity\Tag;
use App\Entity\Video;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends AbstractFixtures
{
    public function loadData(ObjectManager $manager)
    {
        $tags = FixturesConfig::getTags();

        for ($i = 0; $i < 5; $i++) {
            $tag = new Tag();

            $tag->setName($tags[$i]['name']);
            $tag->setBgcolor($tags[$i]['bgcolor']);
            $tag->setColor($tags[$i]['color']);

            $manager->persist($tag);
        }

        $manager->flush();
    }
}
