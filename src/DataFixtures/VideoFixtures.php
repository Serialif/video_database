<?php

namespace App\DataFixtures;

use App\DataFixtures\Config\FixturesConfig;
use App\Entity\Video;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;

class VideoFixtures extends AbstractFixtures
{
    public function loadData(ObjectManager $manager)
    {
        $urls = FixturesConfig::getYoutubeUrls();

        foreach($urls as $url) {
            $date = DateTimeImmutable::createFromMutable($this->faker->dateTime());

            $video = new Video();

            $video->setTitle($this->faker->catchPhrase());
            $video->setDescription($this->faker->paragraph());
            $video->setUrl($url);
            $video->setCreatedAt($date);

            $manager->persist($video);
        }

        $manager->flush();
    }
}
