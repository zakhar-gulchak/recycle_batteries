<?php

namespace Training\Bundle\BatteryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class MainController extends Controller
{

    public function indexAction()
    {
        return $this->helloAction('Fabien');
    }

    public function helloAction($name)
    {
        return $this->render('TrainingBatteryBundle:Default:index.html.twig', array('name' => $name));
    }
}
