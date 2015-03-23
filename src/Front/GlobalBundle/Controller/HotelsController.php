<?php

namespace Front\GlobalBundle\Controller;

use Front\GlobalBundle\Helper\Helper;
use Front\GlobalBundle\Helper\Expedia;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;

class HotelsController extends Controller
{
    public function listeAction($currency, $destination, $arrivalDate, $departureDate, $room1, $room2, $room3, $room4, $room5, $page, $sort, $stars, $rate, $search)
    {
        $session=$this->getRequest()->getSession();
        if($page==1)
        {
            if($session->has('customerSessionId'))
                $customerSessionId=$session->get('customerSessionId');
            $session->clear();
            if(isset($customerSessionId))
                $session->set('customerSessionId', $customerSessionId);
        }
        $Helper=new Helper();
        $Expedia=new Expedia();
        $json=$this->expediaListeHotel($currency, $destination, $arrivalDate, $departureDate, $room1, $room2, $room3, $room4, $room5, $page, $sort, $search, $stars, $rate);
        if($stars!='')
        {
            $starss=explode("-", $stars);
            $MinStars=$starss[0];
            $MaxStars=$starss[1];
        }
        if($rate!='')
        {
            $rates=explode("-", $rate);
            $MinRate=$rates[0];
            $MaxRate=$rates[1];
        }
        if(isset($json['HotelListResponse']['EanWsError']))
            $session->getFlashBag()->add('alert-error', $json['HotelListResponse']['EanWsError']['presentationMessage']);
        else
        {
            if(!$session->has("customerSessionId"))
                $session->set('customerSessionId', $json['HotelListResponse']['customerSessionId']);
            $json=$Helper->encodeDescriptions($json);
            $json=$Helper->Sort($json, $sort);
            $session->set('cacheKey_'.($page+1), $json['HotelListResponse']['cacheKey']);
            $session->set('cacheLocation_'.($page+1), $json['HotelListResponse']['cacheLocation']);
        }
        return $this->render('FrontGlobalBundle:liste:liste.html.twig', array(
                    'list'           =>$json,
                    'session'        =>$session->all(),
                    'currency'       =>$currency,
                    'destination'    =>$destination,
                    'arrivalDate'    =>$arrivalDate,
                    'departureDate'  =>$departureDate,
                    'arrivalDateFR'  =>$Helper->decodeUrlDate($arrivalDate),
                    'departureDateFR'=>$Helper->decodeUrlDate($departureDate),
                    'room1'          =>$room1,
                    'room2'          =>$room2,
                    'room3'          =>$room3,
                    'room4'          =>$room4,
                    'room5'          =>$room5,
                    'page'           =>$page,
                    'sort'           =>$sort,
                    'search'         =>$search,
                    'stars'          =>$stars,
                    'rate'           =>$rate,
                    'MinStars'       =>$MinStars,
                    'MaxStars'       =>$MaxStars,
                    'MinRate'        =>$MinRate,
                    'MaxRate'        =>$MaxRate,
        ));
    }

    public function filtreAction($currency, $destination, $arrivalDate, $departureDate, $room1, $room2, $room3, $room4, $room5, $page, $sort, $stars, $rate, $search)
    {
        $session=$this->getRequest()->getSession();
        $Helper=new Helper();
        $Expedia=new Expedia();
        $request=$this->getRequest();
        $page=1;
        if($request->isMethod("POST"))
        {
            if($request->get('Destination')!=null)
            {
                $destination=$request->get('Destination');
                $search=urlencode($request->get('NameHotel'));
                $currency=$request->get('CurrencyCode');
                $arrivalDate=$Helper->encodeUrlDate($request->get('ArrivalDate'));
                $departureDate=$Helper->encodeUrlDate($request->get('DepartureDate'));
            }
            else
            {
                $rate=$request->get('RateMin').'-'.$request->get('RateMax');
                $stars=$request->get('StarMin').'-'.$request->get('StarMax');
            }
        }
        return $this->redirect($this->generateUrl("listehotels", array(
                            'currency'     =>$currency,
                            'destination'  =>$destination,
                            'arrivalDate'  =>$arrivalDate,
                            'departureDate'=>$departureDate,
                            'room1'        =>$room1,
                            'room2'        =>$room2,
                            'room3'        =>$room3,
                            'room4'        =>$room4,
                            'room5'        =>$room5,
                            'page'         =>$page,
                            'sort'         =>$sort,
                            'search'       =>$search,
                            'stars'        =>$stars,
                            'rate'         =>$rate
        )));
    }

    public function generateAction()
    {
        $Helper=new Helper();
        $request=$this->getRequest();
        $session=$request->getSession();
        if($request->isMethod('POST'))
        {
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
            return $this->redirect($this->generateUrl("listehotels", array(
                                "currency"     =>$request->get("currencyCode"),
                                "destination"  =>urlencode($request->get("destinationString")),
                                "arrivalDate"  =>$Helper->encodeUrlDate($request->get("arrivalDate")),
                                "departureDate"=>$Helper->encodeUrlDate($request->get("departureDate")),
                                "room1"        =>$room1,
                                "room2"        =>$room2,
                                "room3"        =>$room3,
                                "room4"        =>$room4,
                                "room5"        =>$room5,
            )));
        }
        else
            return $this->redirect($this->generateUrl("front_global_homepage"));
    }

    public function expediaListeHotel($currency, $destination, $arrivalDate, $departureDate, $room1, $room2, $room3, $room4, $room5, $page, $sort, $search, $stars, $rate)
    {
        $session=$this->getRequest()->getSession();
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
        if($session->has('customerSessionId'))
            $url .="&customerSessionId=".$session->get('customerSessionId');
        if($page!=1)
        {
            $url .="&cacheKey=".$session->get("cacheKey_".$page);
            $url .="&cacheLocation=".$session->get("cacheLocation_".$page);
        }
        else
        {
            $url .="&destinationString=".$destination;
            $url .="&supplierCacheTolerance=".$this->container->getParameter('supplierCacheTolerance');
            $url .="&arrivalDate=".$Helper->decodeUrlDate($arrivalDate);
            $url .="&departureDate=".$Helper->decodeUrlDate($departureDate);
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
        return $Helper->url_get_contents($url);
    }

}
