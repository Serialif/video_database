<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Form\TagType;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/tag", name="app_tag_")
 */
class TagController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(TagRepository $tagRepository): Response
    {
        return $this->render(
            'admin/tag/index.html.twig',
            [
                'tags' => $tagRepository->findAll(),
            ]
        );
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $tag = new Tag();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isGranted('ROLE_ADMIN')) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($tag);
                $entityManager->flush();

                $this->addFlash('success', 'Le mot clé a bien été ajouté.');
            } else {
                $this->addFlash('warning', 'Le mot clé n\'a pas été ajouté. Vous êtes en mode Démonstration');
            }
            return $this->redirectToRoute('app_tag_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm(
            'admin/tag/new.html.twig',
            [
                'tag' => $tag,
                'form' => $form,
            ]
        );
    }

    /**
     * @Route("/{id}/edit", name="edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Tag $tag): Response
    {
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($this->isGranted('ROLE_ADMIN')) {
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success', 'Le mot clé a bien été modifié.');
            } else {
                $this->addFlash('warning', 'Le mot clé n\'a pas été modifié. Vous êtes en mode Démonstration');
            }

            return $this->redirectToRoute('app_tag_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm(
            'admin/tag/edit.html.twig',
            [
                'tag' => $tag,
                'form' => $form,
            ]
        );
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, Tag $tag): Response
    {
        if ($this->isCsrfTokenValid('delete' . $tag->getId(), $request->request->get('_token'))) {
            if ($this->isGranted('ROLE_ADMIN')) {
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->remove($tag);
                $entityManager->flush();

                $this->addFlash('success', 'Le mot clé a bien été supprimé.');
            } else {
                $this->addFlash('warning', 'Le mot clé n\'a pas été supprimé. Vous êtes en mode Démonstration');
            }
        }

        return $this->redirectToRoute('app_tag_index', [], Response::HTTP_SEE_OTHER);
    }
}
