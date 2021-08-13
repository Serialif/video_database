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
        for ($i = 0; $i < 10; $i++) {
            $tag = new Tag();

            $tag->setName($this->faker->word());
            $tag->setBgcolor($this->faker->hexColor());
            $tag->setColor($this->faker->hexColor());

            $manager->persist($tag);
        }

        $manager->flush();
    }
}
