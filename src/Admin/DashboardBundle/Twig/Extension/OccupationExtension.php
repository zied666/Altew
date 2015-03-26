<?php

namespace Admin\DashboardBundle\Twig\Extension;

class OccupationExtension extends \Twig_Extension
{

    public function getFilters()
    {
        return array(new \Twig_SimpleFilter('occupation', array($this, 'getOccupation')));
    }

    function getOccupation($occ, $type)
    {
        $occ=explode(',', $occ);
        if($type=='A'||$type=='a')
            return $occ[0];
        else
            return count($occ)-1;
    }

    public function getName()
    {
        return 'occupation_extension';
    }

}
