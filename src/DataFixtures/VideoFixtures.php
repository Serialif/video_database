<?php

namespace App\DataFixtures;

use App\Entity\Video;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;

class VideoFixtures extends AbstractFixtures
{
    public function load(ObjectManager $manager)
    {
        $date = new DateTimeImmutable();

        $video = new Video();

        $video->setTitle('title');
        $video->setDescription('description');
        $video->setUrl('url');
        $video->setImage('image');
        $video->setCreatedAt($date);
        $video->setUpdatedAt($date);


        $manager->persist($video);
        $manager->flush();
    }
}
