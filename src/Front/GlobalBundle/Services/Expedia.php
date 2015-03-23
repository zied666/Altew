<?php

namespace Front\GlobalBundle\Services;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Front\GlobalBundle\Entity\Reservation;

class Expedia
{

    public function __construct($securityContext, $entityManager, $urlReservation, $minorRev, $cid, $apiKey, $customerUserAgent, $supplierCacheTolerance, $supplierType, $creditCardType, $creditCardNumber, $creditCardIdentifier, $creditCardExpirationMonth, $creditCardExpirationYear)
    {
        $this->securityContext=$securityContext;
        $this->em=$entityManager;
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
            var_dump($json);
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
