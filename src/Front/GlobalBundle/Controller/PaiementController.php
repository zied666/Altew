<?php

namespace Front\GlobalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Front\GlobalBundle\Helper\Helper;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\DateTime;
use Front\GlobalBundle\Entity\Reservation;
use Front\GlobalBundle\Entity\Room;
use Symfony\Component\Security\Acl\Exception\Exception;

class PaiementController extends Controller
{

    public function notificationAction()
    {
        $em=$this->getDoctrine()->getManager();
        $request=$this->getRequest();
        $ref=$request->get('Reference');
        $act=$request->get('Action');
        $par=$request->get('Param');
        $reservation = new Reservation();
        $reservation=$em->getRepository("FrontGlobalBundle:Reservation")->find($ref);
        if(!$reservation)
            throw new \Exception('Cette réservation n\'existe pas');
        switch($act)
        {
            case "DETAIL":
                $json=$this->container->get("expedia")->valider($ref);
                if(isset($json['HotelRoomReservationResponse']['EanWsError']))
                    return new Response("");
                else
                    return new Response("Reference=".$ref."&Action=".$act."&Reponse=".$reservation->getMontant());
                break;
            case "ERREUR":
                $em->remove($reservation);
                $em->flush();
                return new Response("Reference=".$ref."&Action=".$act."&Reponse=OK");
                break;
            case "ACCORD":
                $em->remove($reservation);
                $em->flush();
                return new Response("Reference=".$ref."&Action=".$act."&Reponse=OK");
                break;
            case "REFUS":
                $em->remove($reservation);
                $em->flush();
                return new Response("Reference=".$ref."&Action=".$act."&Reponse=OK");
                break;
            case "ANNULATION":
                $em->remove($reservation);
                $em->flush();
                return new Response("Reference=".$ref."&Action=".$act."&Reponse=OK");
                break;
        }
    }

    public function succesAction()
    {
        $em=$this->getDoctrine()->getManager();
        $session=$this->getRequest()->getSession();
        $request=$this->getRequest();
        $ref=$request->get('Reference');
        return $this->render("FrontGlobalBundle:paiement:succes.html.twig");
    }

    public function echecAction()
    {
        $em=$this->getDoctrine()->getManager();
        $request=$this->getRequest();
        $ref=$request->get('Reference');
        return $this->render("FrontGlobalBundle:paiement:echec.html.twig");
    }

}
