<?php

namespace App\Controller;

use App\Entity\Document;
use App\Entity\DocumentProperty;
use App\Form\AdvancedSearchType;
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
            $data = $form->getData();
            $documents = $this->getDoctrine()->getRepository(Document::class)->findByKeywords($data['search']);
        }
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
            'form' => $form->createView(),
            'documents' => $documents,
            'search' => $data['search']
        ]);
    }

    /**
     * @Route("/advanced", name="advanced")
     */
    public function advanced(Request $request): Response
    {
        $documents = null;
        $data = ['type' => '', 'parameter' => '', 'search' => ''];
        $form = $this->createForm(AdvancedSearchType::class, $data);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $documents = $this->getDoctrine()->getRepository(Document::class)->findByKeywords($data['search'],$data['type'],$data['parameter'],$data['authors']);
        }

        return $this->render('default/advanced.html.twig', [
            'form' => $form->createView(),
            'documents' => $documents,
            'search' => $data['search']
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

    /**
     * @Route("/analytics", name="analytics")
     */
    public function analytics(): Response
    {
        $scalars = $this->getDoctrine()->getRepository(Document::class)->findDistinctYears();
        $years = [];
        $total = [];
        $published = [];
        $unpublished = [];
        $awards = [];
        foreach ($scalars as $scalar) {
            $docRepo = $this->getDoctrine()->getRepository(Document::class);
            $year = $scalar['yearCreated'];
            if ($year <= 0) {
                continue;
            }
            $years[$year] = $year;

            $total[$year] = $docRepo->countByYear($year);
            $pub = $docRepo->countPropertyByYear($year, DocumentProperty::PROPERTY_PUBLICATION);
            if ($pub > 0) {
                $published[$year] = $pub;
                $unpublished[$year] = $total[$year] - $published[$year];
            }

            $awd = $docRepo->countPropertyByYear($year, DocumentProperty::PROPERTY_AWARD);
            if ($awd > 0) {
                $awards[$year] = $awd;
            }
        }
        asort($years);
        arsort($total);
        arsort($published);
        arsort($unpublished);
        arsort($awards);
        return $this->render('default/analytics.html.twig', [
            'years' => $years,
            'total' => $total,
            'published' => $published,
            'unpublished' => $unpublished,
            'awards' => $awards
        ]);
    }
}
