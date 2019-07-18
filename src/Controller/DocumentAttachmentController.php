<?php

namespace App\Controller;

use App\Entity\DocumentAttachment;
use App\Form\DocumentAttachmentType;
use App\Repository\DocumentAttachmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/document/attachment")
 */
class DocumentAttachmentController extends AbstractController
{
    /**
     * @Route("/", name="document_attachment_index", methods={"GET"})
     */
    public function index(DocumentAttachmentRepository $documentAttachmentRepository): Response
    {
        return $this->render('document_attachment/index.html.twig', [
            'document_attachments' => $documentAttachmentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="document_attachment_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $documentAttachment = new DocumentAttachment();
        $form = $this->createForm(DocumentAttachmentType::class, $documentAttachment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($documentAttachment);
            $entityManager->flush();

            return $this->redirectToRoute('document_attachment_index');
        }

        return $this->render('document_attachment/new.html.twig', [
            'document_attachment' => $documentAttachment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="document_attachment_show", methods={"GET"})
     */
    public function show(DocumentAttachment $documentAttachment): Response
    {
        return $this->render('document_attachment/show.html.twig', [
            'document_attachment' => $documentAttachment,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="document_attachment_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, DocumentAttachment $documentAttachment): Response
    {
        $form = $this->createForm(DocumentAttachmentType::class, $documentAttachment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('document_attachment_index');
        }

        return $this->render('document_attachment/edit.html.twig', [
            'document_attachment' => $documentAttachment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="document_attachment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, DocumentAttachment $documentAttachment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$documentAttachment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($documentAttachment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('document_attachment_index');
    }
}
