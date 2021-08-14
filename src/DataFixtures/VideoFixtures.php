<?php

namespace App\DataFixtures;

use App\DataFixtures\Config\FixturesConfig;
use App\Entity\Tag;
use App\Entity\Video;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;

class VideoFixtures extends AbstractFixtures
{
    public function loadData(ObjectManager $manager)
    {
        $urls = FixturesConfig::getYoutubeUrls();

        foreach ($urls as $url) {
            $date = DateTimeImmutable::createFromMutable($this->faker->dateTime());

            $video = new Video();

            /** @phpstan-ignore-next-line */
            $video->setTitle($this->faker->catchPhrase());
            $video->setDescription($this->faker->paragraph());
            $video->setUrl($url);
            $video->setCreatedAt($date);

            $tagRepository = $manager->getRepository(Tag::class);

            $tags = $tagRepository->findAll();

            $tagsNumber = rand(0, count($tags));
            $usedTags = [];

            for ($i = 0; $i < $tagsNumber; $i++) {
                while (count($usedTags) < $tagsNumber) {
                    $tagNumber = rand(0, count($tags) - 1);
                    if (!in_array($tagNumber, $usedTags)) {
                        $usedTags[] = $tagNumber;
                        $video->addTag($tags[$tagNumber]);
                    }
                }
            }
            $manager->persist($video);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [TagFixtures::class];
    }
}
