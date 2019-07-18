<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\DocumentAttachment;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Route("/document")
 */
class DocumentController extends AbstractController
{
    public function moveUploadedFile($fileData) {
        if ($fileData) {
            $newFileName = uniqid().'.'.$fileData->guessExtension();
            $fileData->move(
                $this->getParameter('document_directory'),
                $newFileName
            );
            return $newFileName;
        }
        return false;
    }

    public function persistAttachment(EntityManagerInterface $entityManager, FormInterface $form, Document $document)
    {
        $abstract = null;
        $fulltext = null;
        foreach ($document->getDocumentAttachments() as $attachment) {
            switch ($attachment->getType()) {
                case DocumentAttachment::TYPE_ABSTRACT:
                    $abstract = $attachment;
                    break;
                case DocumentAttachment::TYPE_FULLTEXT:
                    $fulltext = $attachment;
                    break;
            }
        }
        if ($abstractFile = $this->moveUploadedFile($form['abstract']->getData())) {
            if (null === $abstract) {
                $abstract = new DocumentAttachment();
            }
            $abstract->setType(DocumentAttachment::TYPE_ABSTRACT);
            $abstract->setPath($abstractFile);
            $document->addDocumentAttachment($abstract);
            $entityManager->persist($abstract);
        }
        if ($fulltextFile = $this->moveUploadedFile($form['fulltext']->getData())) {
            if (null === $fulltext) {
                $fulltext = new DocumentAttachment();
            }
            $fulltext->setType(DocumentAttachment::TYPE_FULLTEXT);
            $fulltext->setPath($fulltextFile);
            $document->addDocumentAttachment($fulltext);
            $entityManager->persist($fulltext);
        }
    }
    /**
     * @Route("/", name="document_index", methods={"GET"})
     */
    public function index(DocumentRepository $documentRepository): Response
    {
        return $this->render('document/index.html.twig', [
            'documents' => $documentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="document_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $document = new Document();
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $this->persistAttachment($entityManager, $form, $document);
            $entityManager->persist($document);
            $entityManager->flush();

            return $this->redirectToRoute('document_index');
        }

        return $this->render('document/new.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="document_show", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function show(Document $document): Response
    {
        return $this->render('document/show.html.twig', [
            'document' => $document,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="document_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Document $document): Response
    {
        $form = $this->createForm(DocumentType::class, $document);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $this->persistAttachment($entityManager, $form, $document);
            $entityManager->flush();

            return $this->redirectToRoute('document_index');
        }

        return $this->render('document/edit.html.twig', [
            'document' => $document,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="document_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Document $document): Response
    {
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($document);
            $entityManager->flush();
        }

        return $this->redirectToRoute('document_index');
    }
}
