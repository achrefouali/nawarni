<?php

namespace SettingBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('SettingBundle:Default:index.html.twig');
    }
}
