<?php

namespace App\Controller;

use App\Entity\DocumentAuthor;
use App\Form\DocumentAuthorType;
use App\Repository\DocumentAuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/document/author")
 */
class DocumentAuthorController extends AbstractController
{
    /**
     * @Route("/", name="document_author_index", methods={"GET"})
     */
    public function index(DocumentAuthorRepository $documentAuthorRepository): Response
    {
        return $this->render('document_author/index.html.twig', [
            'document_authors' => $documentAuthorRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="document_author_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $documentAuthor = new DocumentAuthor();
        $form = $this->createForm(DocumentAuthorType::class, $documentAuthor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($documentAuthor);
            $entityManager->flush();

            return $this->redirectToRoute('document_author_index');
        }

        return $this->render('document_author/new.html.twig', [
            'document_author' => $documentAuthor,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="document_author_show", methods={"GET"})
     */
    public function show(DocumentAuthor $documentAuthor): Response
    {
        return $this->render('document_author/show.html.twig', [
            'document_author' => $documentAuthor,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="document_author_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, DocumentAuthor $documentAuthor): Response
    {
        $form = $this->createForm(DocumentAuthorType::class, $documentAuthor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('document_author_index');
        }

        return $this->render('document_author/edit.html.twig', [
            'document_author' => $documentAuthor,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="document_author_delete", methods={"DELETE"})
     */
    public function delete(Request $request, DocumentAuthor $documentAuthor): Response
    {
        if ($this->isCsrfTokenValid('delete'.$documentAuthor->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($documentAuthor);
            $entityManager->flush();
        }

        return $this->redirectToRoute('document_author_index');
    }
}
