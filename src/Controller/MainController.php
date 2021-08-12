<?php

namespace App\Controller;

use App\Repository\VideoRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/", name="app_home")
     */
    public function index(Request $request, VideoRepository $videoRepository, PaginatorInterface $paginator): Response
    {
        $data = $videoRepository->findAll();

        $videos = $paginator->paginate(
            $data,
            $request->query->getInt('page', 1),
            20
        );

        return $this->render('main/index.html.twig', [
            'videos' => $videos,
        ]);
    }
}
