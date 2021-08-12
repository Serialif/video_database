<?php

namespace App\Twig;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class YoutubeExtension extends AbstractExtension
{
    private ParameterBagInterface $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('get_image', [$this, 'getImage']),
        ];
    }

//    public function getFunctions(): array
//    {
//        return [
//            new TwigFunction('get_image', [$this, 'get_image']),
//        ];
//    }

    /**
     * Return the thumbnail url of a youtube video url
     * @param string $url
     * @return string
     */
    public function getImage(string $url): string
    {
        $videoId = $this->getVideoID($url);

        $youtubeThumbnailUrl = $this->parameterBag->get('youtube_thumbnail_base_url');

        return str_replace('ID', $videoId, $youtubeThumbnailUrl);
    }

    /**
     * Return the video ID of a youtube video url
     * @param string $url
     * @return string|null
     */
    private function getVideoID(string $url): ?string
    {
        $urlComponents = parse_url($url);


        if (isset($urlComponents['query'])) {
            parse_str($urlComponents['query'], $params);
            return $params['v'] ?? null;
        }
        return null;
    }
}
