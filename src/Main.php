<?php

namespace UnderstrapEstate;

use UnderstrapEstate\Builder\CityBuilder;
use WpToolKit\Entity\View;
use WpToolKit\Entity\ScriptType;
use WpToolKit\Controller\ViewLoader;
use UnderstrapEstate\Builder\EstateBuilder;
use UnderstrapEstate\Controller\Post\CityPost;
use UnderstrapEstate\Controller\Post\EstatePost;
use WpToolKit\Factory\ServiceFactory;

class Main
{
    private ViewLoader $viewLoader;

    public function __construct()
    {
        $this->viewLoader = new ViewLoader();
        $this->initViews();
        $estatePost = new EstatePost();
        $cityPost = new CityPost();
        $cityPost->addToSubMenu($estatePost->getPost());
        $estateBuilder = new EstateBuilder($estatePost, $cityPost, $this->viewLoader);
        $estateBuilder->create();
        $cityBuilder = new CityBuilder($cityPost, $estatePost, $this->viewLoader);
        $cityBuilder->create();
        $scripts = ServiceFactory::getService('ScriptController');

        $scripts->addStyle(
            'understrap-estate-metabox',
            '/understrap-estate/assets/style/MetaBox.css',
            ScriptType::ADMIN
        );
    }

    private function initViews(): void
    {
        $basePathTemplate = WP_PLUGIN_DIR . '/understrap-estate/src/Template';

        $this->viewLoader->add(
            new View(
                'CityDescription',
                $basePathTemplate . '/MetaBox/CityDescriptionView.php',
                []
            )
        );

        $this->viewLoader->add(
            new View(
                'EstateAttribution',
                $basePathTemplate . '/MetaBox/EstateAttributionView.php',
                []
            )
        );

        $this->viewLoader->add(
            new View(
                'EstateCityId',
                $basePathTemplate . '/MetaBox/EstateCityIdView.php',
                []
            )
        );

        $this->viewLoader->add(
            new View(
                'EstateDescription',
                $basePathTemplate . '/MetaBox/EstateDescriptionView.php',
                []
            )
        );

        $this->viewLoader->add(
            new View(
                'ListEstate',
                $basePathTemplate . '/MetaBox/ListEstateView.php',
                []
            )
        );
    }
}
