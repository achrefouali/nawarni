<?php

namespace ClaimBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('ClaimBundle:Default:index.html.twig');
    }
}
