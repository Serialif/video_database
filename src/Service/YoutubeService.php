<?php


namespace App\Service;


use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class YoutubeService
{
    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    /**
     * Return the video ID of a youtube video url
     * @param string $url
     * @return string|null
     */
    public function getVideoID(string $url): ?string
    {
        $urlComponents = parse_url($url);

        parse_str($urlComponents['query'], $params);

        return $params['v'] ?? null;
    }

    /**
     * Return the thumbnail url of a youtube video url
     * @param string $url
     * @return string
     */
    public function getImageURL(string $url): string
    {
        $videoId = $this->getVideoID($url);

        $youtubeThumbnailUrl = $this->parameterBag->get('youtube_thumbnail_base_url');

        return str_replace('ID', $videoId, $youtubeThumbnailUrl);
    }
}