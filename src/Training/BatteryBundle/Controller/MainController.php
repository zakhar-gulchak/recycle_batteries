<?php

namespace Training\BatteryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Training\BatteryBundle\Entity\Battery;
use Training\BatteryBundle\Entity\BatteryRepository;

class MainController extends Controller
{
    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->helloAction('Fabien');
    }

    /**
     * @param $name string
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function helloAction($name)
    {
        return $this->render('TrainingBatteryBundle:Default:index.html.twig', array('name' => $name));
    }

    /**
     * @param $request Request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function addBatteryAction(Request $request)
    {
        $battery = new Battery();
        $form = $this->createFormBuilder($battery)
            ->add('type', 'text')
            ->add('count', 'integer')
            ->add('name', 'text', array('required' => false))
            ->add('save', 'submit', array('label' => 'Create'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($battery);
            $em->flush();

            return $this->redirect($this->generateUrl('training_battery_succespage'));
        }

        return $this->render('TrainingBatteryBundle:Default:addBattery.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function statisticsAction()
    {
        /** @var BatteryRepository $repository */
        $repository = $this->getDoctrine()
            ->getRepository('TrainingBatteryBundle:Battery');
        return $this->render('TrainingBatteryBundle:Default:statistics.html.twig',
            array('batteries' => $repository->findAllGroupedByType()));
    }

    public function successAction()
    {
        return $this->render('TrainingBatteryBundle:Default:success.html.twig');
    }
}
