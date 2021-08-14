<?php

namespace App\Tests\Service;

use App\Service\YoutubeService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;

class YoutubeServiceTest extends TestCase
{
    private ParameterBag $parameterBag;

    protected function setUp(): void
    {
        $this->parameterBag = new ParameterBag(
            [
                'youtube_thumbnail_base_url' => 'http://img.youtube.com/vi/ID/0.jpg'
            ]
        );
    }

    public function testGetVideoId(): void
    {
        $url = "https://www.youtube.com/watch?v=ssesNFcv8lk&t=24s";
        $youtubeService = new YoutubeService($this->parameterBag);
        $videoId = $youtubeService->getVideoID($url);

        $this->assertEquals('ssesNFcv8lk', $videoId);
        $this->assertNotEquals('notEquals', $videoId);
    }

    public function testgetImageUrl(): void
    {
        $url = "https://www.youtube.com/watch?v=ssesNFcv8lk&t=24s";
        $youtubeService = new YoutubeService($this->parameterBag);
        $imageUrl = $youtubeService->getImageUrl($url);

        $this->assertEquals('http://img.youtube.com/vi/ssesNFcv8lk/0.jpg', $imageUrl);
        $this->assertNotEquals('http://img.youtube.com/vi/notEquals/0.jpg', $imageUrl);
    }
}
