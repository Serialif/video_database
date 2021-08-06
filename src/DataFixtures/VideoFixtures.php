<?php

namespace App\DataFixtures;

use App\Entity\Video;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;

class VideoFixtures extends AbstractFixtures
{
    public function loadData(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $date = DateTimeImmutable::createFromMutable($this->faker->dateTime());

            $video = new Video();

            $video->setTitle($this->faker->words(5, true));
            $video->setDescription($this->faker->paragraphs(3, true));
            $video->setUrl($this->faker->url());
            $video->setImage($this->faker->imageUrl(320, 240));
            $video->setCreatedAt($date);

            $manager->persist($video);
        }

        $manager->flush();
    }
}
