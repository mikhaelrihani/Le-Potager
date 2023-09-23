<?php

namespace App\Controller\Faq;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class FaqMainController extends AbstractController
{
    /**
     * @Route("/faq", name="app_faq_main_home", methods={"GET"})
     */
    public function home()
    {
        return $this->render('faq/home.html.twig');
    }

}