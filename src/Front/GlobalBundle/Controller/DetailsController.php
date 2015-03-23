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
        $json=$this->expediaDetailsHotel($idhotel, $currency, $arrivalDate, $departureDate, $room1, $room2, $room3, $room4, $room5);
        if(isset($json['HotelRoomAvailabilityResponse']['EanWsError']))
        {
            $session->getFlashBag()->add('alert-error', $json['HotelRoomAvailabilityResponse']['EanWsError']['presentationMessage']);
            return $this->redirect($this->generateUrl("front_global_homepage"));
        }
        $json=$Helper->EncodeCaracters($json);
        $rooms=$Helper->getRooms($room1, $room2, $room3, $room4, $room5);
        $country=$this->ip_info("Visitor", "Country");
        return $this->render('FrontGlobalBundle:hotel:hotel.html.twig', array(
                    'hotel'         =>$json['HotelRoomAvailabilityResponse'],
                    'country'       =>$country,
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

    public function ip_info($ip=NULL, $purpose="location", $deep_detect=TRUE)
    {
        $output=NULL;
        if(filter_var($ip, FILTER_VALIDATE_IP)===FALSE)
        {
            $ip=$_SERVER["REMOTE_ADDR"];
            if($deep_detect)
            {
                if(filter_var(@$_SERVER['HTTP_X_FORWARDED_FOR'], FILTER_VALIDATE_IP))
                    $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
                if(filter_var(@$_SERVER['HTTP_CLIENT_IP'], FILTER_VALIDATE_IP))
                    $ip=$_SERVER['HTTP_CLIENT_IP'];
            }
        }
        $purpose=str_replace(array("name", "\n", "\t", " ", "-", "_"), NULL, strtolower(trim($purpose)));
        $support=array("country", "countrycode", "state", "region", "city", "location", "address");
        $continents=array(
            "AF"=>"Africa",
            "AN"=>"Antarctica",
            "AS"=>"Asia",
            "EU"=>"Europe",
            "OC"=>"Australia (Oceania)",
            "NA"=>"North America",
            "SA"=>"South America"
        );
        if(filter_var($ip, FILTER_VALIDATE_IP)&&in_array($purpose, $support))
        {
            $ipdat=@json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));
            if(@strlen(trim($ipdat->geoplugin_countryCode))==2)
            {
                switch($purpose)
                {
                    case "location":
                        $output=array(
                            "city"          =>@$ipdat->geoplugin_city,
                            "state"         =>@$ipdat->geoplugin_regionName,
                            "country"       =>@$ipdat->geoplugin_countryName,
                            "country_code"  =>@$ipdat->geoplugin_countryCode,
                            "continent"     =>@$continents[strtoupper($ipdat->geoplugin_continentCode)],
                            "continent_code"=>@$ipdat->geoplugin_continentCode
                        );
                        break;
                    case "address":
                        $address=array($ipdat->geoplugin_countryName);
                        if(@strlen($ipdat->geoplugin_regionName)>=1)
                            $address[]=$ipdat->geoplugin_regionName;
                        if(@strlen($ipdat->geoplugin_city)>=1)
                            $address[]=$ipdat->geoplugin_city;
                        $output=implode(", ", array_reverse($address));
                        break;
                    case "city":
                        $output=@$ipdat->geoplugin_city;
                        break;
                    case "state":
                        $output=@$ipdat->geoplugin_regionName;
                        break;
                    case "region":
                        $output=@$ipdat->geoplugin_regionName;
                        break;
                    case "country":
                        $output=@$ipdat->geoplugin_countryName;
                        break;
                    case "countrycode":
                        $output=@$ipdat->geoplugin_countryCode;
                        break;
                }
            }
        }
        return $output;
    }

}
