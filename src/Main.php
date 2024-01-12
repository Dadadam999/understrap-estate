<?php

namespace UnderstrapEstate;

use WpToolKit\Entity\View;
use WpToolKit\Entity\MetaPoly;
use WpToolKit\Entity\MetaPolyType;
use WpToolKit\Controller\ViewLoader;
use UnderstrapEstate\Controller\Post\CityPost;
use UnderstrapEstate\Controller\Post\EstatePost;
use UnderstrapEstate\Controller\MetaBox\CityListEstate;
use UnderstrapEstate\Controller\MetaBox\EstateCityIdBox;

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
