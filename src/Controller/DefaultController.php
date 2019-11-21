<?php 

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{
    /**
     * Homepage
     * 
     * @Route("/", name="app_homepage")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request) : Response
    {
        return $this->render('index.html.twig');
    }
}
