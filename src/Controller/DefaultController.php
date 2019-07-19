<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function index()
    {
        return $this->render('default/index.html.twig', [
            'controller_name' => 'DefaultController',
        ]);
    }

    /**
     * @Route("/advanced", name="advanced")
     */
    public function advanced()
    {
        return $this->render('default/advanced.html.twig', [
        ]);
    }

    /**
     * @Route("/browse", name="browse")
     */
    public function browse()
    {
        return $this->render('default/browse.html.twig', [
        ]);
    }
}
