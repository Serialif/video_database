<?php

namespace App\Controller\Admin;

use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
use App\Service\YoutubeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/video", name="app_video_")
 */
class VideoController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(VideoRepository $videoRepository): Response
    {
        return $this->render(
            'admin/video/index.html.twig',
            [
                'videos' => $videoRepository->findBy([], ['createdAt' => 'DESC']),
            ]
        );
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $video = new Video();
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isGranted('ROLE_ADMIN')) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($video);
                $entityManager->flush();

                $this->addFlash('success', 'La vidéo a bien été ajoutée.');
            } else {
                $this->addFlash('warning', 'La vidéo n\'a pas été ajoutée. Vous êtes en mode DEMONSTRATION');
            }
            return $this->redirectToRoute('app_video_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm(
            'admin/video/new.html.twig',
            [
                'video' => $video,
                'form' => $form,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Video $video): Response
    {
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isGranted('ROLE_ADMIN')) {
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success', 'La vidéo a bien été modifiée.');
            } else {
                $this->addFlash('warning', 'La vidéo n\'a pas été modifiée. Vous êtes en mode DEMONSTRATION');
            }
            return $this->redirectToRoute('app_video_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm(
            'admin/video/edit.html.twig',
            [
                'video' => $video,
                'form' => $form,
            ]
        );
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, Video $video): Response
    {
        if ($this->isCsrfTokenValid('delete' . $video->getId(), $request->request->get('_token'))) {
            if ($this->isGranted('ROLE_ADMIN')) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($video);
                $entityManager->flush();
                $this->addFlash('success', 'La vidéo a bien été supprimée.');
            } else {
                $this->addFlash('warning', 'La vidéo n\'a pas été supprimée. Vous êtes en mode DEMONSTRATION');
            }
        }


        return $this->redirectToRoute('app_video_index', [], Response::HTTP_SEE_OTHER);
    }
}
