<?php

namespace App\Controller;

use App\Entity\Document;
use App\Form\SearchType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index(Request $request): Response
    {
        $documents = null;
        $data = ['search' => ''];
        $form = $this->createForm(SearchType::class, $data);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $documents = $this->getDoctrine()->getRepository(Document::class)->findByKeywords($data['search']);
        }
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'form' => $form->createView(),
            'documents' => $documents
        ]);
    }

    /**
     * @Route("/advanced", name="advanced")
     */
    public function advanced(): Response
    {
        return $this->render('default/advanced.html.twig', [
        ]);
    }

    /**
     * @Route("/browse", name="browse")
     */
    public function browse(): Response
    {
        return $this->render('default/browse.html.twig', [
        ]);
    }
}
