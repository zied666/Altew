<?php

namespace Front\GlobalBundle\Helper;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class Helper extends Controller
{

    public function getHrefTravelNow($json,$rateKey)
    {
        foreach($json as $room)
        {
            if($room['rateCode']==$rateKey)
                return $room['deepLink'];
        }
    }

        public function encodeDescriptions($json)
    {
        if($json["HotelListResponse"]["HotelList"]["@size"]<=1)
        {
            $json["HotelListResponse"]["HotelList"]["HotelSummary"]['shortDescription']=html_entity_decode($json["HotelListResponse"]["HotelList"]["HotelSummary"]['shortDescription']);
            return $json;
        }
        for($i=0; $i<count($json["HotelListResponse"]["HotelList"]["HotelSummary"]); $i++)
        {
            $json["HotelListResponse"]["HotelList"]["HotelSummary"][$i]['shortDescription']=html_entity_decode($json["HotelListResponse"]["HotelList"]["HotelSummary"][$i]['shortDescription']);
        }
        return $json;
    }

    public function getRooms($room1, $room2, $room3, $room4, $room5)
    {
        for($i=1; $i<=5; $i++)
        {
            if(${'room'.$i}!=0)
            {
                if(${'room'.$i}==0)
                    $rooms[$i]=0;
                else
                {
                    $data=explode(',', ${'room'.$i});
                    $j=1;
                    $Enf=array();
                    foreach($data as $val)
                    {
                        if($j==1)
                            $rooms[$i]['adulte']=$val;
                        else
                            $Enf[$j-1]=$val;
                        $j++;
                    }
                    $rooms[$i]['enfants']=$Enf;
                }
            }
        }
        return $rooms;
    }

    public function EncodeCaracters($json)
    {
        if(isset($json["HotelRoomAvailabilityResponse"]["HotelDetails"]["areaInformation"]))
            $json["HotelRoomAvailabilityResponse"]["HotelDetails"]["areaInformation"]=html_entity_decode($json["HotelRoomAvailabilityResponse"]["HotelDetails"]["areaInformation"]);
        if(isset($json["HotelRoomAvailabilityResponse"]["HotelDetails"]["propertyDescription"]))
            $json["HotelRoomAvailabilityResponse"]["HotelDetails"]["propertyDescription"]=html_entity_decode($json["HotelRoomAvailabilityResponse"]["HotelDetails"]["propertyDescription"]);
        if(isset($json["HotelRoomAvailabilityResponse"]["HotelDetails"]["roomInformation"]))
            $json["HotelRoomAvailabilityResponse"]["HotelDetails"]["roomInformation"]=html_entity_decode($json["HotelRoomAvailabilityResponse"]["HotelDetails"]["roomInformation"]);
        if(isset($json["HotelRoomAvailabilityResponse"]["HotelDetails"]["hotelPolicy"]))
            $json["HotelRoomAvailabilityResponse"]["HotelDetails"]["hotelPolicy"]=html_entity_decode($json["HotelRoomAvailabilityResponse"]["HotelDetails"]["hotelPolicy"]);
        if(isset($json["HotelRoomAvailabilityResponse"]["HotelDetails"]["checkInInstructions"]))
            $json["HotelRoomAvailabilityResponse"]["HotelDetails"]["checkInInstructions"]=html_entity_decode($json["HotelRoomAvailabilityResponse"]["HotelDetails"]["checkInInstructions"]);
        if(isset($json["HotelRoomAvailabilityResponse"]["HotelDetails"]["knowBeforeYouGoDescription"]))
            $json["HotelRoomAvailabilityResponse"]["HotelDetails"]["knowBeforeYouGoDescription"]=html_entity_decode($json["HotelRoomAvailabilityResponse"]["HotelDetails"]["knowBeforeYouGoDescription"]);
        if(isset($json["HotelRoomAvailabilityResponse"]["HotelDetails"]["mandatoryFeesDescription"]))
            $json["HotelRoomAvailabilityResponse"]["HotelDetails"]["mandatoryFeesDescription"]=html_entity_decode($json["HotelRoomAvailabilityResponse"]["HotelDetails"]["mandatoryFeesDescription"]);
        if(isset($json["HotelRoomAvailabilityResponse"]["HotelDetails"]["locationDescription"]))
            $json["HotelRoomAvailabilityResponse"]["HotelDetails"]["locationDescription"]=html_entity_decode($json["HotelRoomAvailabilityResponse"]["HotelDetails"]["locationDescription"]);
        if(isset($json["HotelRoomAvailabilityResponse"]["HotelDetails"]["diningDescription"]))
            $json["HotelRoomAvailabilityResponse"]["HotelDetails"]["diningDescription"]=html_entity_decode($json["HotelRoomAvailabilityResponse"]["HotelDetails"]["diningDescription"]);
        if(isset($json["HotelRoomAvailabilityResponse"]["HotelDetails"]["amenitiesDescription"]))
            $json["HotelRoomAvailabilityResponse"]["HotelDetails"]["amenitiesDescription"]=html_entity_decode($json["HotelRoomAvailabilityResponse"]["HotelDetails"]["amenitiesDescription"]);
        if(isset($json["HotelRoomAvailabilityResponse"]["HotelDetails"]["businessAmenitiesDescription"]))
            $json["HotelRoomAvailabilityResponse"]["HotelDetails"]["businessAmenitiesDescription"]=html_entity_decode($json["HotelRoomAvailabilityResponse"]["HotelDetails"]["businessAmenitiesDescription"]);
        if(isset($json["HotelRoomAvailabilityResponse"]["HotelDetails"]["roomDetailDescription"]))
            $json["HotelRoomAvailabilityResponse"]["HotelDetails"]["roomDetailDescription"]=html_entity_decode($json["HotelRoomAvailabilityResponse"]["HotelDetails"]["roomDetailDescription"]);
        return $json;
    }

    public function sort($json, $sort)
    {
        if($json["HotelListResponse"]["HotelList"]["@size"]<=1)
            return $json;
        elseif($sort=="NOSORT")
            return $json;
        elseif($sort=="PRICE_AVERAGE")
        {
            for($i=0; $i<count($json["HotelListResponse"]["HotelList"]["HotelSummary"]); $i++)
            {
                for($j=$i; $j<count($json["HotelListResponse"]["HotelList"]["HotelSummary"]); $j++)
                {
                    if($json["HotelListResponse"]["HotelList"]["HotelSummary"][$i]['RoomRateDetailsList']['RoomRateDetails']['RateInfos']['RateInfo']['ChargeableRateInfo']['@averageRate']>=$json["HotelListResponse"]["HotelList"]["HotelSummary"][$j]['RoomRateDetailsList']['RoomRateDetails']['RateInfos']['RateInfo']['ChargeableRateInfo']['@averageRate'])
                    {
                        $abc=$json["HotelListResponse"]["HotelList"]["HotelSummary"][$i];
                        $json["HotelListResponse"]["HotelList"]["HotelSummary"][$i]=$json["HotelListResponse"]["HotelList"]["HotelSummary"][$j];
                        $json["HotelListResponse"]["HotelList"]["HotelSummary"][$j]=$abc;
                    }
                }
            }
            return $json;
        }
        elseif($sort=="PROMO")
        {
            return $json;
        }
        elseif($sort=="ALPHA")
        {
            for($i=0; $i<count($json["HotelListResponse"]["HotelList"]["HotelSummary"]); $i++)
            {
                for($j=$i; $j<count($json["HotelListResponse"]["HotelList"]["HotelSummary"]); $j++)
                {
                    if($json["HotelListResponse"]["HotelList"]["HotelSummary"][$i]['name']>=$json["HotelListResponse"]["HotelList"]["HotelSummary"][$j]['name'])
                    {
                        $abc=$json["HotelListResponse"]["HotelList"]["HotelSummary"][$i];
                        $json["HotelListResponse"]["HotelList"]["HotelSummary"][$i]=$json["HotelListResponse"]["HotelList"]["HotelSummary"][$j];
                        $json["HotelListResponse"]["HotelList"]["HotelSummary"][$j]=$abc;
                    }
                }
            }
            return $json;
        }
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

    public function url_get_contents($Url)
    {
        $output="";
        if(!function_exists('curl_init'))
        {
            die('CURL is not installed!');
        }
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

}

?>