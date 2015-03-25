<?php

namespace Front\GlobalBundle\Services;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Front\GlobalBundle\Entity\Reservation;

class Expedia
{

    public function __construct($session, $request, $securityContext, $entityManager, $url, $urlHotel, $urlReservation, $minorRev, $cid, $apiKey, $customerUserAgent, $supplierCacheTolerance, $supplierType, $creditCardType, $creditCardNumber, $creditCardIdentifier, $creditCardExpirationMonth, $creditCardExpirationYear)
    {
        $this->session=$session;
        $this->request=$request;
        $this->securityContext=$securityContext;
        $this->em=$entityManager;

        $this->url=$url;
        $this->urlHotel=$urlHotel;
        $this->urlReservation=$urlReservation;
        $this->minorRev=$minorRev;
        $this->cid=$cid;
        $this->apiKey=$apiKey;
        $this->customerUserAgent=$customerUserAgent;
        $this->supplierCacheTolerance=$supplierCacheTolerance;
        $this->supplierType=$supplierType;

        $this->creditCardType=$creditCardType;
        $this->creditCardNumber=$creditCardNumber;
        $this->creditCardIdentifier=$creditCardIdentifier;
        $this->creditCardExpirationMonth=$creditCardExpirationMonth;
        $this->creditCardExpirationYear=$creditCardExpirationYear;
    }

    public function valider($id)
    {
        $reservation=$this->em->getRepository("FrontGlobalBundle:Reservation")->find($id);
        if($reservation)
        {
            $array=array(
                'cid'                      =>urlencode($this->cid),
                'apiKey'                   =>urlencode($this->apiKey),
                'customerUserAgent'        =>urlencode($this->customerUserAgent),
                'customerIpAddress'        =>urlencode($_SERVER['REMOTE_ADDR']),
                'locale'                   =>urlencode("en_US"),
                'minorRev'                 =>urlencode($this->minorRev),
                'currencyCode'             =>urlencode($reservation->getCurrency()),
                'hotelId'                  =>urlencode($reservation->getIdhotel()),
                'arrivalDate'              =>urlencode($reservation->getArrivaleDate()->format('m/d/Y')),
                'departureDate'            =>urlencode($reservation->getDepartureDate()->format('m/d/Y')),
                'supplierType'             =>urlencode($this->supplierType),
                'rateKey'                  =>urlencode($reservation->getRateKey()),
                'roomTypeCode'             =>urlencode($reservation->getRoomTypeCode()),
                'rateCode'                 =>urlencode($reservation->getRateCode()),
                'chargeableRate'           =>urlencode($reservation->getChargeableRate()),
                'email'                    =>urlencode($reservation->getEmail()),
                'firstName'                =>urlencode($reservation->getFirstName()),
                'lastName'                 =>urlencode($reservation->getLastName()),
                'homePhone'                =>urlencode($reservation->getHomePhone()),
                'workPhone'                =>urlencode($reservation->getWorkPhone()),
                'creditCardType'           =>urlencode($this->creditCardType),
                'creditCardNumber'         =>urlencode($this->creditCardNumber),
                'creditCardIdentifier'     =>urlencode($this->creditCardIdentifier),
                'creditCardExpirationMonth'=>urlencode($this->creditCardExpirationMonth),
                'creditCardExpirationYear' =>urlencode($this->creditCardExpirationYear),
                'address1'                 =>urlencode($reservation->getAddress()),
                'city'                     =>urlencode($reservation->getCity()),
                'stateProvinceCode'        =>urlencode($reservation->getCountryCode()),
                'countryCode'              =>urlencode($reservation->getCountryCode()),
                'postalCode'               =>urlencode($reservation->getPostalCode())
//                
//                'city'                     =>urlencode("Bellevue"),
//                'stateProvinceCode'        =>urlencode("WA"),
//                'countryCode'              =>urlencode("US"),
//                'postalCode'               =>urlencode("98004")
            );
            $i=1;
            foreach($reservation->getRooms() as $room)
            {
                $array['room'.$i]=urlencode($room->getOcupation());
                $array['room'.$i.'FirstName']=urlencode($room->getFirstName());
                $array['room'.$i.'LastName']=urlencode($room->getLastName());
                $array['room'.$i.'BedTypeId']=urlencode($room->getBed());
                $array['room'.$i.'SmokingPreference']=urlencode("NS");
                $i++;
            }
            $json=$this->url_post_contents($this->urlReservation, $array);
            return $json;
        }
    }

    function url_post_contents($url, $fields)
    {
        extract($_POST);
        $fields_string="";
        foreach($fields as $key=> $value)
            $fields_string .= $key.'='.$value.'&';
        rtrim($fields_string, '&');
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, count($fields));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);
        $result=curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    public function DetailsHotel($idhotel, $currency, $arrivalDate, $departureDate, $room1, $room2, $room3, $room4, $room5)
    {
        $url=$this->urlHotel;
        $url .="currencyCode=".$currency;
        $url .="&minorRev=".$this->minorRev;
        $url .="&cid=".$this->cid;
        $url .="&apiKey=".$this->apiKey;
        $url .="&customerUserAgent=".$this->customerUserAgent;
        $url .="&customerIpAddress=".$_SERVER['REMOTE_ADDR'];
        $url .="&locale=".$this->request->getLocale();
        if($this->session->has('customerSessionId'))
            $url .="&customerSessionId=".$this->session->get('customerSessionId');
        $url .="&arrivalDate=".$this->decodeUrlDate($arrivalDate);
        $url .="&departureDate=".$this->decodeUrlDate($departureDate);
        $url .="&hotelId=".$idhotel;
        $url .="&includeDetails=true";
        $url .="&includeRoomImages=true";
        $url .="&options=HOTEL_DETAILS,ROOM_TYPES,ROOM_AMENITIES,PROPERTY_AMENITIES,HOTEL_IMAGES,SUPPLIERS";
        for($i=1; $i<=5; $i++)
        {
            if(${"room".$i}!=0)
                $url.="&room".$i."=".${"room".$i};
        }
        return $this->url_get_contents($url);
    }

    public function listHotels($currency, $destination, $arrivalDate, $departureDate, $room1, $room2, $room3, $room4, $room5, $page, $sort, $search, $stars, $rate)
    {
        $url=$this->url;
        $url .="currencyCode=".$currency;
        $url .="&minorRev=".$this->minorRev;
        $url .="&cid=".$this->cid;
        $url .="&numberOfResults=20";
        $url .="&apiKey=".$this->apiKey;
        $url .="&customerUserAgent=".$this->customerUserAgent;
        $url .="&customerIpAddress=".$_SERVER['REMOTE_ADDR'];
        $url .="&locale=".$this->request->getLocale();
        if($this->session->has('customerSessionId'))
            $url .="&customerSessionId=".$this->session->get('customerSessionId');
        if($page!=1)
        {
            $url .="&cacheKey=".$this->session->get("cacheKey_".$page);
            $url .="&cacheLocation=".$this->session->get("cacheLocation_".$page);
        }
        else
        {
            $url .="&destinationString=".$destination;
            $url .="&supplierCacheTolerance=".$this->supplierCacheTolerance;
            $url .="&arrivalDate=".$this->decodeUrlDate($arrivalDate);
            $url .="&departureDate=".$this->decodeUrlDate($departureDate);
            for($i=1; $i<=5; $i++)
            {
                if(${"room".$i}!=0)
                    $url.="&room".$i."=".${"room".$i};
            }
            if($sort!='NOSORT')
                $url .="&sort=".$sort;
            if($rate!='')
            {
                $rates=explode('-', $rate);
                if($rates[0]!='')
                    $url .="&minRate=".$rates[0];
                if($rates[1]!='')
                    $url .="&maxRate=".$rates[1];
            }
            if($stars!='')
            {
                $starss=explode('-', $stars);
                if($starss[0]!='')
                    $url .="&minStarRating=".$starss[0];
                if($starss[1]!='')
                    $url .="&maxStarRating=".$starss[1];
            }
            if($search!='')
                $url .="&propertyName=".$search;
        }
        return $this->url_get_contents($url);
    }

    public function url_get_contents($Url)
    {
        $output="";
        if(!function_exists('curl_init'))
            die('CURL is not installed!');
        $ch=curl_init();
        curl_setopt($ch, CURLOPT_URL, $Url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $output=curl_exec($ch);
        $httpCode=curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        if($httpCode==400)
            return 'No donuts for you.';
        if($httpCode==200)
            $output=json_decode($output, true);
        return $output;
    }

    public static function encodeUrlDate($date)
    {
        $data=explode("/", $date);
        return $data[2].'-'.$data[0].'-'.$data[1];
    }

    public static function decodeUrlDate($date)
    {
        $data=explode("-", $date);
        return $data[1].'/'.$data[2].'/'.$data[0];
    }

}
