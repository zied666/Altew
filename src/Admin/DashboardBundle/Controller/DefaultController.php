<?php

namespace Admin\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Front\GlobalBundle\Entity\Reservation;
use Front\GlobalBundle\Entity\Room;
use Symfony\Component\HttpFoundation\Session\Session;
use Front\GlobalBundle\Entity\ReservationRepository;
use Admin\DashboardBundle\Form\UserType;

class DefaultController extends Controller
{

    public function indexAction()
    {
        $em=$this->getDoctrine()->getManager();
        $session=$this->getRequest()->getSession();
        $reserP=$em->getRepository("FrontGlobalBundle:Reservation")->getReservationPaye();
        $reserNP=$em->getRepository("FrontGlobalBundle:Reservation")->getReservationNotPaye();
        $reservations=$em->getRepository("FrontGlobalBundle:Reservation")->findAll();
        $user=$this->container->get('security.context')->getToken()->getUser();
        $form=$this->createForm(new UserType(), $user);
        $request=$this->getRequest();
        if($request->isMethod('POST'))
        {
            $form->bind($request);
            if($form->isValid())
            {
                $user=$form->getData();
                $em->persist($user);
                $em->flush();
                return $this->redirect($this->generateUrl("dashboard", array('type'=>'pass')));
            }
        }
        return $this->render('AdminDashboardBundle:Default:index.html.twig', array(
                    'form'        =>$form->createView(),
                    'reserP'      =>$reserP,
                    'reserNP'     =>$reserNP,
                    'reservations'=>$reservations
        ));
    }

}
