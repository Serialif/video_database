<?php

namespace App\Controller;

use App\Repository\VideoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="main")
     */
    public function index(VideoRepository $videoRepository): Response
    {
        $videos = $videoRepository->findAll();

        return $this->render('main/index.html.twig', [
            'videos' => $videos,
        ]);
    }
}
