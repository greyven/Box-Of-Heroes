<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class IndexController extends Controller
{
    /**
     * @Route("/", name="index")
     */
    public function indexAction()
    {
        return $this->render('index.html.twig');
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function adminAction()
    {
        return $this->render('Admin/index.html.twig');
    }

    /**
     * @Route("/agenda", name="agenda")
     */
    public function agendaAction()
    {
        return $this->render('agenda.html.twig');
    }
}