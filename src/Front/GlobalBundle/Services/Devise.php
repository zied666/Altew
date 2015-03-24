<?php

namespace Front\GlobalBundle\Services;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Front\GlobalBundle\Entity\Reservation;

class Devise
{

    public function __construct()
    {
        
    }

    function convertCurrency($amount, $from, $to)
    {
        $url="https://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
        $data=file_get_contents($url);
        preg_match("/<span class=bld>(.*)<\/span>/", $data, $converted);
        $converted=preg_replace("/[^0-9.]/", "", $converted[1]);
        return round($converted, 3);
    }

}
