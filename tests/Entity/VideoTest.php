<?php

namespace App\Tests\Entity;

use App\Entity\Video;
use DateTimeImmutable;
use PHPUnit\Framework\TestCase;

class VideoTest extends TestCase
{
    public function testIsTrue(): void
    {
        $date = new DateTimeImmutable();

        $video = new Video();

        $video->setTitle('title');
        $video->setDescription('description');
        $video->setUrl('url');
        $video->setCreatedAt($date);
        $video->setUpdatedAt($date);

        $this->assertTrue($video->getTitle() === 'title');
        $this->assertTrue($video->getDescription() === 'description');
        $this->assertTrue($video->getUrl() === 'url');
        $this->assertTrue($video->getCreatedAt() === $date);
        $this->assertTrue($video->getUpdatedAt() === $date);
    }

    public function testIsFalse(): void
    {
        $date = new DateTimeImmutable();

        $video = new Video();

        $video->setTitle('title');
        $video->setDescription('description');
        $video->setUrl('url');
        $video->setCreatedAt($date);
        $video->setUpdatedAt($date);

        $this->assertFalse($video->getTitle() === 'false');
        $this->assertFalse($video->getDescription() === 'false');
        $this->assertFalse($video->getUrl() === 'false');
        $this->assertFalse($video->getCreatedAt() === new DateTimeImmutable());
        $this->assertFalse($video->getUpdatedAt() === new DateTimeImmutable());
    }

    public function testIsEmpty(): void
    {
        $video = new Video();

        $this->assertEmpty($video->getTitle());
        $this->assertEmpty($video->getDescription());
        $this->assertEmpty($video->getUrl());
        $this->assertEmpty($video->getCreatedAt());
        $this->assertEmpty($video->getUpdatedAt());
    }
}
