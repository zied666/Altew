<?php

namespace Admin\DashboardBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Front\GlobalBundle\Entity\Reservation;
use Front\GlobalBundle\Entity\Room;
use Symfony\Component\HttpFoundation\Session\Session;
use Front\GlobalBundle\Entity\ReservationRepository;

class DefaultController extends Controller
{

    public function indexAction($page)
    {
        $user = $this->container->get('security.context')->getToken()->getUser();
        if(!$user)
            return $this->redirect($this->generateUrl("fos_user_security_logout"));
        $em=$this->getDoctrine()->getManager();
        $request=$this->getRequest();
        $paginator=$this->get('knp_paginator');
        $reserP=$em->getRepository("FrontGlobalBundle:Reservation")->getReservationPaye();
        $reserNP=$em->getRepository("FrontGlobalBundle:Reservation")->getReservationNotPaye();
        $findreservations=$em->getRepository("FrontGlobalBundle:Reservation")->allOrderByDate();
        $reservations=$this->get('knp_paginator')->paginate($findreservations, $page, 10);
        return $this->render('AdminDashboardBundle:Default:index.html.twig', array(
                    'reserP'      =>$reserP,
                    'reserNP'     =>$reserNP,
                    'reservations'=>$reservations
        ));
    }

}
