<?php

namespace Vendor\UnderstrapEstate;

use Vendor\UnderstrapEstate\Controller\MetaBox\CityListEstate;
use Vendor\UnderstrapEstate\Controller\MetaBox\EstateCityIdBox;
use Vendor\UnderstrapEstate\Entity\MetaPoly;
use Vendor\UnderstrapEstate\Entity\ScriptType;
use Vendor\UnderstrapEstate\Entity\MetaPolyType;
use Vendor\UnderstrapEstate\Controller\Post\CityPost;
use Vendor\UnderstrapEstate\Controller\Post\EstatePost;
use Vendor\UnderstrapEstate\Controller\ScriptController;

class Main
{
    public function __construct()
    {
        $estateType = new EstatePost();
        $cityType = new CityPost();
        $cityType->registerSubMenu($estateType->getPost(), $cityType->getPost());

        $cityIdPoly = new MetaPoly(
            'city_id',
            MetaPolyType::INTENGER,
            __('City id', 'understrap-estate-plugin')
        );

        $estateType->addMetaPoly($estateType->getPost(), $cityIdPoly);
        $estateCityIdBox = new EstateCityIdBox($estateType->getPost(), $cityType->getPost(), $cityIdPoly);
        $cityListEstate = new CityListEstate($cityType->getPost(), $estateType->getPost(), $cityIdPoly);
    }
}
