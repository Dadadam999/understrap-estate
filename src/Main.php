<?php

namespace Vendor\UnderstrapEstate;

use Vendor\UnderstrapEstate\Entity\View;
use Vendor\UnderstrapEstate\Entity\MetaPoly;
use Vendor\UnderstrapEstate\Entity\MetaPolyType;
use Vendor\UnderstrapEstate\Controller\ViewLoader;
use Vendor\UnderstrapEstate\Controller\Post\CityPost;
use Vendor\UnderstrapEstate\Controller\Post\EstatePost;
use Vendor\UnderstrapEstate\Controller\MetaBox\CityListEstate;
use Vendor\UnderstrapEstate\Controller\MetaBox\EstateCityIdBox;

class Main
{
    public function __construct()
    {
        $this->initViews();

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

    /**
     * @return View[]
     */
    private function initViews(): void
    {
        $basePathTemplate = WP_PLUGIN_DIR . '/understrap-estate/src/Template';

        ViewLoader::add(
            new View(
                'CityDescription',
                $basePathTemplate . '/MetaBox/CityDescriptionView.php',
                []
            )
        );

        ViewLoader::add(
            new View(
                'EstateAttribution',
                $basePathTemplate . '/MetaBox/EstateAttributionView.php',
                []
            )
        );

        ViewLoader::add(
            new View(
                'EstateCityId',
                $basePathTemplate . '/MetaBox/EstateCityIdView.php',
                []
            )
        );

        ViewLoader::add(
            new View(
                'EstateDescription',
                $basePathTemplate . '/MetaBox/EstateDescriptionView.php',
                []
            )
        );

        ViewLoader::add(
            new View(
                'ListEstate',
                $basePathTemplate . '/MetaBox/ListEstateView.php',
                []
            )
        );
    }
}
