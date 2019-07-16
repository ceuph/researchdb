<?php

namespace App\Controller;

use App\Entity\DocumentProperty;
use App\Form\DocumentPropertyType;
use App\Repository\DocumentPropertyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/document/property")
 */
class DocumentPropertyController extends AbstractController
{
    /**
     * @Route("/", name="document_property_index", methods={"GET"})
     */
    public function index(DocumentPropertyRepository $documentPropertyRepository): Response
    {
        return $this->render('document_property/index.html.twig', [
            'document_properties' => $documentPropertyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="document_property_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $documentProperty = new DocumentProperty();
        $form = $this->createForm(DocumentPropertyType::class, $documentProperty);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($documentProperty);
            $entityManager->flush();

            return $this->redirectToRoute('document_property_index');
        }

        return $this->render('document_property/new.html.twig', [
            'document_property' => $documentProperty,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="document_property_show", methods={"GET"})
     */
    public function show(DocumentProperty $documentProperty): Response
    {
        return $this->render('document_property/show.html.twig', [
            'document_property' => $documentProperty,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="document_property_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, DocumentProperty $documentProperty): Response
    {
        $form = $this->createForm(DocumentPropertyType::class, $documentProperty);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('document_property_index');
        }

        return $this->render('document_property/edit.html.twig', [
            'document_property' => $documentProperty,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="document_property_delete", methods={"DELETE"})
     */
    public function delete(Request $request, DocumentProperty $documentProperty): Response
    {
        if ($this->isCsrfTokenValid('delete'.$documentProperty->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($documentProperty);
            $entityManager->flush();
        }

        return $this->redirectToRoute('document_property_index');
    }
}
