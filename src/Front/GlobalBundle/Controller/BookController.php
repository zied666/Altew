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

class BookController extends Controller
{

    public function currecyAction($idhotel, $stars, $currency, $arrivalDate, $departureDate, $room1, $room2, $room3, $room4, $room5, $rateKey, $roomTypeCode, $rateCode, $chargeableRate)
    {
        $Helper=new Helper();
        $request=$this->getRequest();
        $session=$request->getSession();
        if($request->isMethod("POST"))
        {
            if($request->get("currencyCode")=="TND")
            {
                return $this->redirect($this->generateUrl("Booking", array(
                                    'currency'      =>$currency,
                                    'idhotel'       =>$idhotel,
                                    'stars'         =>$stars,
                                    'room1'         =>$room1,
                                    'room2'         =>$room2,
                                    'room3'         =>$room3,
                                    'room4'         =>$room4,
                                    'room5'         =>$room5,
                                    'arrivalDate'   =>$arrivalDate,
                                    'departureDate' =>$departureDate,
                                    'rateKey'       =>$rateKey,
                                    'roomTypeCode'  =>$roomTypeCode,
                                    'rateCode'      =>$rateCode,
                                    'chargeableRate'=>$chargeableRate
                )));
            }else
            {
                $currency=$request->get("currencyCode");
                $json=$this->expediaDetailsHotel($idhotel, $currency, $arrivalDate, $departureDate, $room1, $room2, $room3, $room4, $room5);
                $href=$Helper->getHrefTravelNow($json['HotelRoomAvailabilityResponse']['HotelRoomResponse'],$rateCode);
                $session->set('href',  str_replace("55505", "347646", str_replace(";", "&", $href)));
                return $this->redirect($this->generateUrl("TravelNow"));
            }
        }
        return $this->render('FrontGlobalBundle:book:currency.html.twig', array(
                    'currency'      =>$currency,
                    'idhotel'       =>$idhotel,
                    'stars'         =>$stars,
                    'room1'         =>$room1,
                    'room2'         =>$room2,
                    'room3'         =>$room3,
                    'room4'         =>$room4,
                    'room5'         =>$room5,
                    'arrivalDate'   =>$Helper->decodeUrlDate($arrivalDate),
                    'departureDate' =>$Helper->decodeUrlDate($departureDate),
                    'arrivalDate2'  =>$arrivalDate,
                    'departureDate2'=>$departureDate,
                    'rateKey'       =>$rateKey,
                    'roomTypeCode'  =>$roomTypeCode,
                    'rateCode'      =>$rateCode,
                    'chargeableRate'=>$chargeableRate
        ));
    }

    public function validationAction($idhotel, $stars, $currency, $arrivalDate, $departureDate, $room1, $room2, $room3, $room4, $room5, $rateKey, $roomTypeCode, $rateCode, $chargeableRate)
    {
        $Helper=new Helper();
        $em=$this->getDoctrine()->getManager();
        $request=$this->getRequest();
        $session=$request->getSession();
        $rooms=$Helper->getRooms($room1, $room2, $room3, $room4, $room5);
        if($request->isMethod("POST"))
        {
            $reservation=new Reservation();
            $reservation->setFirstName($request->get("firstName"));
            $reservation->setLastName($request->get("lastName"));
            $reservation->setHomePhone($request->get("homePhone"));
            $reservation->setWorkPhone($request->get("workPhone"));
            $reservation->setWorkPhone($request->get("workPhone"));
            $reservation->setAddress($request->get("address"));
            $reservation->setEmail($request->get("email"));
            $reservation->setCity($request->get("city"));
            $reservation->setCountryCode($request->get("countryCode"));
            $reservation->setPostalCode($request->get("postalCode"));
            $reservation->setArrivaleDate(new \DateTime($arrivalDate));
            $reservation->setDepartureDate(new \DateTime($departureDate));
            $reservation->setChargeableRate($chargeableRate);
            $reservation->setCurrency($currency);
            $reservation->setIdhotel($idhotel);
            $reservation->setRateCode($rateCode);
            $reservation->setRateKey($rateKey);
            $reservation->setRoomTypeCode($roomTypeCode);
            $reservation->setDateReservation(new \DateTime());
            $reservation->setMontant($this->container->get("Devise")->convertCurrency($chargeableRate, $currency, "TND"));
            $i=1;
            foreach($rooms as $room)
            {
                $Room=new Room();
                $Room->setOcupation(${'room'.$i});
                $Room->setFirstName($request->get("firstName".$i));
                $Room->setLastName($request->get("lastName".$i));
                $Room->setBed($request->get("bed".$i));
                $Room->setReservation($reservation);
                $em->persist($Room);
                $i++;
            }
            $em->persist($reservation);
            $em->flush();
            //$session->getFlashBag()->add('alert-success', "Votre réservation a été effectué avec succes");
            return new Response("Redirection vers tunisie monetique....");
        }
    }

    public function bookAction($idhotel, $stars, $currency, $arrivalDate, $departureDate, $room1, $room2, $room3, $room4, $room5, $rateKey, $roomTypeCode, $rateCode, $chargeableRate)
    {
        $Helper=new Helper();
        $json=$this->expediaDetailsHotel($idhotel, $currency, $arrivalDate, $departureDate, $room1, $room2, $room3, $room4, $room5);
        $session=$this->getRequest()->getSession();
        $nights=(strtotime($Helper->decodeUrlDate($departureDate))-strtotime($Helper->decodeUrlDate($arrivalDate)))/86400;
        $rooms=$Helper->getRooms($room1, $room2, $room3, $room4, $room5);
        return $this->render('FrontGlobalBundle:book:book.html.twig', array(
                    'hotel'         =>$json['HotelRoomAvailabilityResponse'],
                    'idhotel'       =>$idhotel,
                    'stars'         =>$stars,
                    'currency'      =>$currency,
                    'rooms'         =>$rooms,
                    'room1'         =>$room1,
                    'room2'         =>$room2,
                    'room3'         =>$room3,
                    'room4'         =>$room4,
                    'room5'         =>$room5,
                    'nights'        =>$nights,
                    'arrivalDate'   =>$Helper->decodeUrlDate($arrivalDate),
                    'departureDate' =>$Helper->decodeUrlDate($departureDate),
                    'arrivalDate2'  =>$arrivalDate,
                    'departureDate2'=>$departureDate,
                    'rateKey'       =>$rateKey,
                    'roomTypeCode'  =>$roomTypeCode,
                    'rateCode'      =>$rateCode,
                    'chargeableRate'=>$chargeableRate
        ));
    }

    public function travelNowAction()
    {
        $session=$this->getRequest()->getSession();
        return $this->render('FrontGlobalBundle:book:travelNow.html.twig', array(
                    'href'=>$session->get('href')
        ));
    }

    public function travelNowAjaxAction()
    {
        $session=$this->getRequest()->getSession();
        $session->set('href', $this->getRequest()->get("href"));
        return new Response('ok');
    }

    public function expediaDetailsHotel($idhotel, $currency, $arrivalDate, $departureDate, $room1, $room2, $room3, $room4, $room5)
    {
        $session=$this->getRequest()->getSession();
        $Helper=new Helper();
        $url=$this->container->getParameter('urlHotel');
        $url .="currencyCode=".$currency;
        $url .="&minorRev=".$this->container->getParameter('minorRev');
        $url .="&cid=".$this->container->getParameter('cid');
        $url .="&apiKey=".$this->container->getParameter('apiKey');
        $url .="&customerUserAgent=".$this->container->getParameter('customerUserAgent');
        $url .="&customerIpAddress=".$_SERVER['REMOTE_ADDR'];
        $url .="&locale=".$this->getRequest()->getLocale();
        if($session->has('customerSessionId'))
            $url .="&customerSessionId=".$session->get('customerSessionId');
        $url .="&arrivalDate=".$Helper->decodeUrlDate($arrivalDate);
        $url .="&departureDate=".$Helper->decodeUrlDate($departureDate);
        $url .="&hotelId=".$idhotel;
        $url .="&includeDetails=true";
        $url .="&includeRoomImages=true";
        $url .="&options=HOTEL_DETAILS,ROOM_TYPES,ROOM_AMENITIES,PROPERTY_AMENITIES,HOTEL_IMAGES,SUPPLIERS";
        for($i=1; $i<=5; $i++)
        {
            if(${"room".$i}!=0)
                $url.="&room".$i."=".${"room".$i};
        }
        return $Helper->url_get_contents($url);
    }

}
