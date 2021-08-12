<?php

namespace App\DataFixtures;

use App\Entity\Video;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;

class VideoFixtures extends AbstractFixtures
{
    public function loadData(ObjectManager $manager)
    {
        $urls = require 'src/DataFixtures/Config/youtubeUrls.php';

        foreach($urls as $url) {
            $date = DateTimeImmutable::createFromMutable($this->faker->dateTime());

            $video = new Video();

            $video->setTitle($this->faker->catchPhrase());
            $video->setDescription($this->faker->paragraphs(3, true));
            $video->setUrl($url);
            $video->setCreatedAt($date);

            $manager->persist($video);
        }

        $manager->flush();
    }
}
