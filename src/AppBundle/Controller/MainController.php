<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{
    public function homepageAction($name)
    {
        return $this->render('main/homepage.html.twig');
    }
}
