<?php

namespace UnderstrapEstate\Builder;

use WpToolKit\Entity\MetaPoly;
use WpToolKit\Entity\MetaPolyType;
use WpToolKit\Controller\ViewLoader;
use UnderstrapEstate\Controller\Post\CityPost;
use UnderstrapEstate\Controller\Post\EstatePost;
use UnderstrapEstate\Controller\MetaBox\CityListEstate;
use UnderstrapEstate\Controller\MetaBox\CityDescriptionBox;

final class CityBuilder
{
    private array $metaPoly;

    public function __construct(
        public CityPost $cityPost,
        public EstatePost $estatePost,
        public ViewLoader $viewLoader
    ) {
    }

    public function create()
    {
        $this->createMetaPoly();
        $this->createMetaBox();
    }

    private function createMetaPoly()
    {
        $this->metaPoly = [
            'city_description' => new MetaPoly(
                'city_description',
                MetaPolyType::STRING,
                __('Description', 'understrap-estate-plugin')
            ),
            'city_id' => new MetaPoly(
                'city_id',
                MetaPolyType::INTENGER,
                __('City id', 'understrap-estate-plugin')
            )

        ];

        foreach ($this->metaPoly as $metapoly) {
            $this->estatePost->addMetaPoly($metapoly);
        }
    }

    private function createMetaBox()
    {
        new CityDescriptionBox($this->cityPost->getPost(), $this->viewLoader, $this->metaPoly['city_description']);
        new CityListEstate($this->cityPost->getPost(), $this->viewLoader, $this->estatePost->getPost(), $this->metaPoly['city_id']);
    }
}
