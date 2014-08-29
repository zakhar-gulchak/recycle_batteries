<?php

namespace Training\Bundle\BatteryBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Training\Bundle\BatteryBundle\Entity\Battery;

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
        $form = $this->createFormBuilder()
            ->add('type', 'text')
            ->add('count', 'integer')
            ->add('name', 'text')
            ->add('save', 'submit', array('label' => 'Create'))
            ->getForm();
        $form->handleRequest($request);
        if ($form->isValid()) {
            $data = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $repository = $em->getRepository('TrainingBatteryBundle:Battery');
            /** @var Battery $battery */
            if ($battery = $repository->findOneBy(array('type' => $data['type']))) {
                $battery->setCount($battery->getCount() + $data['count']);
            } else {
                $battery = new Battery();
                $battery->setCount($data['count']);
                $battery->setName($data['name']);
                $battery->setType($data['type']);
            }
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
        $repository = $this->getDoctrine()
            ->getRepository('TrainingBatteryBundle:Battery');
        return $this->render('TrainingBatteryBundle:Default:statistics.html.twig',
            array('batteries' => $repository->findAll()));
    }

    public function successAction()
    {
        return $this->render('TrainingBatteryBundle:Default:success.html.twig');
    }
}
