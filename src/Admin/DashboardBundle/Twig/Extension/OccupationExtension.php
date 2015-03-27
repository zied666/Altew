<?php

namespace Admin\DashboardBundle\Twig\Extension;

class OccupationExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return array(
            new \Twig_SimpleFilter('occupation', array($this, 'getOccupation')),
            new \Twig_SimpleFilter('convert', array($this, 'convertion'))
        );
    }

    function getOccupation($occ, $type)
    {
        $occ=explode(',', $occ);
        if($type=='A'||$type=='a')
            return $occ[0];
        else
            return count($occ)-1;
    }
    
    public function convertion($amount, $from, $to)
    {
        $url="https://www.google.com/finance/converter?a=$amount&from=$from&to=$to";
        $data=file_get_contents($url);
        preg_match("/<span class=bld>(.*)<\/span>/", $data, $converted);
        $converted=preg_replace("/[^0-9.]/", "", $converted[1]);
        return round($converted, 3);
    }

    public function getName()
    {
        return 'occupation_extension';
    }

}
