<?php

namespace Front\GlobalBundle\Helper;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Front\GlobalBundle\Helper\Helper;

class Expedia extends Controller
{

    public function expediaListeHotel($currency, $destination, $arrivalDate, $departureDate, $room1, $room2, $room3, $room4, $room5, $page, $sort, $search, $stars, $rate)
    {
        $Helper=new Helper();
        $url=$this->container->getParameter('url');
        $url .="currencyCode=".$currency;
        $url .="&minorRev=".$this->container->getParameter('minorRev');
        $url .="&cid=".$this->container->getParameter('cid');
        $url .="&numberOfResults=20";
        $url .="&apiKey=".$this->container->getParameter('apiKey');
        $url .="&customerUserAgent=".$this->container->getParameter('customerUserAgent');
        $url .="&customerIpAddress=".$_SERVER['REMOTE_ADDR'];
        $url .="&locale=".$this->getRequest()->getLocale();
//        if(isset($_SESSION['customerSessionId'])&&!empty($_SESSION['customerSessionId']))
//            $url .="&customerSessionId=".$_SESSION['customerSessionId'];
        $url .="&destinationString=".$destination;
        $url .="&supplierCacheTolerance=".$this->container->getParameter('supplierCacheTolerance');
        $url .="&arrivalDate=".$Helper->decodeUrlDate($arrivalDate);
        $url .="&departureDate=".$Helper->decodeUrlDate($departureDate);
        for($i=1; $i<=5; $i++)
        {
            if(${"room".$i}!=0)
                $url.="&room".$i."=".${"room".$i};
        }
        if($search!='')
            $url .="&propertyName=".$search;
        if($sort!='NOSORT')
            $url .="&sort=".$sort;
        if($rate!='')
        {
            $rates=explode('-', $rate);
            $url .="&minRate=".$rates[0];
            $url .="&maxRate=".$rates[1];
        }
        if($stars!='')
        {
            $starss=explode('-', $stars);
            $url .="&minRate=".$starss[0];
            $url .="&maxRate=".$starss[1];
        }

        return $Helper->url_get_contents($url);
    }

}

?>