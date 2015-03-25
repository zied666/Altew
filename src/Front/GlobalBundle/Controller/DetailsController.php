<?php

namespace Front\GlobalBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Front\GlobalBundle\Helper\Helper;
use Symfony\Component\HttpFoundation\Session\Session;

class DetailsController extends Controller
{
    public function indexAction($idhotel, $stars, $currency, $arrivalDate, $departureDate, $room1, $room2, $room3, $room4, $room5)
    {
        $session=$this->getRequest()->getSession();
        $Helper=new Helper();
        //Update SESSION
        if($session->has('customerSessionId'))
            $customerSessionId=$session->get('customerSessionId');
        $session->clear();
        if(isset($customerSessionId))
            $session->set('customerSessionId', $customerSessionId);
        //UPDATE SESSION
        $json=$this->container->get('expedia')->DetailsHotel($idhotel, $currency, $arrivalDate, $departureDate, $room1, $room2, $room3, $room4, $room5);
        if(isset($json['HotelRoomAvailabilityResponse']['EanWsError']))
        {
            $session->getFlashBag()->add('alert-error', $json['HotelRoomAvailabilityResponse']['EanWsError']['presentationMessage']);
            return $this->redirect($this->generateUrl("front_global_homepage"));
        }
        $json=$Helper->EncodeCaracters($json);
        $rooms=$Helper->getRooms($room1, $room2, $room3, $room4, $room5);
        return $this->render('FrontGlobalBundle:hotel:hotel.html.twig', array(
                    'hotel'         =>$json['HotelRoomAvailabilityResponse'],
                    'room1'         =>$room1,
                    'room2'         =>$room2,
                    'room3'         =>$room3,
                    'room4'         =>$room4,
                    'room5'         =>$room5,
                    'idhotel'       =>$idhotel,
                    'stars'         =>$stars,
                    'currency'      =>$currency,
                    'arrivalDate'   =>$Helper->decodeUrlDate($arrivalDate),
                    'departureDate' =>$Helper->decodeUrlDate($departureDate),
                    'arrivalDate2'  =>$arrivalDate,
                    'departureDate2'=>$departureDate,
                    'rooms'         =>$rooms
        ));
    }

    public function editAction()
    {
        $Helper=new Helper();
        $request=$this->getRequest();
        $session=$request->getSession();
        for($i=0; $i<=5; $i++)
        {
            if($i>$request->get("rooms"))
                ${"room".$i}=0;
            else
            {
                ${"room".$i}=$request->get("adulteRoom".$i);
                if($request->get("enfantRoom".$i)>0)
                {
                    for($j=1; $j<=$request->get("enfantRoom".$i); $j++)
                        ${"room".$i}.=",".$request->get("chambre".$i."age".$j);
                }
            }
        }
        return $this->redirect($this->generateUrl("detailshotel", array(
                            "currency"     =>$request->get("currencyCode"),
                            "idhotel"      =>$request->get("idhotel"),
                            "stars"        =>$request->get("stars"),
                            "arrivalDate"  =>$Helper->encodeUrlDate($request->get("arrivalDate")),
                            "departureDate"=>$Helper->encodeUrlDate($request->get("departureDate")),
                            "room1"        =>$room1,
                            "room2"        =>$room2,
                            "room3"        =>$room3,
                            "room4"        =>$room4,
                            "room5"        =>$room5,
        )));
    }
}
