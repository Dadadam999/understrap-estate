<?php

namespace UnderstrapEstate\Builder;

use WpToolKit\Entity\MetaPoly;
use WpToolKit\Entity\MetaPolyType;
use WpToolKit\Controller\ViewLoader;
use UnderstrapEstate\Controller\Post\EstatePost;
use UnderstrapEstate\Controller\Taxonomy\EstateType;
use UnderstrapEstate\Controller\MetaBox\EstateCityIdBox;
use UnderstrapEstate\Controller\MetaBox\EstateAttributionBox;
use UnderstrapEstate\Controller\MetaBox\EstateDescriptionBox;
use UnderstrapEstate\Controller\Post\CityPost;

class EstateBuilder
{
    /**
     * @var MetaPoly[]
     */
    private array $metaPoly;

    /**
     * @var MetaPoly[]
     */
    private array $attribution;

    public function __construct(
        public EstatePost $estatePost,
        public CityPost $cityPost,
        public ViewLoader $viewLoader
    ) {
    }

    public function create()
    {
        $this->createMetaPoly();
        $this->createTaxonomy();
        $this->createMetaBox();
    }

    private function createMetaPoly()
    {
        $this->metaPoly = [
            'estate_address' => new MetaPoly(
                'estate_address',
                MetaPolyType::STRING,
                __('Address', 'understrap-estate-plugin')
            ),
            'estate_area' => new MetaPoly(
                'estate_area',
                MetaPolyType::INTENGER,
                __('Area')
            ),
            'estate_living_area' => new MetaPoly(
                'estate_living_area',
                MetaPolyType::INTENGER,
                __('Living area', 'understrap-estate-plugin')
            ),
            'estate_floor' => new MetaPoly(
                'estate_floor',
                MetaPolyType::INTENGER,
                __('Floor', 'understrap-estate-plugin')
            ),
            'estate_cost' => new MetaPoly(
                'estate_cost',
                MetaPolyType::INTENGER,
                __('Cost', 'understrap-estate-plugin')
            ),
            'estate_description' => new MetaPoly(
                'estate_description',
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

        $this->attribution[] = $this->metaPoly['estate_address'];
        $this->attribution[] = $this->metaPoly['estate_area'];
        $this->attribution[] = $this->metaPoly['estate_living_area'];
        $this->attribution[] = $this->metaPoly['estate_floor'];
        $this->attribution[] = $this->metaPoly['estate_cost'];
    }

    private function createTaxonomy()
    {
        new EstateType($this->estatePost->getPost());
    }

    private function createMetaBox()
    {
        new EstateDescriptionBox(
            $this->estatePost->getPost(),
            $this->viewLoader,
            $this->metaPoly['estate_description']
        );

        new EstateAttributionBox(
            $this->estatePost->getPost(),
            $this->viewLoader,
            $this->attribution
        );

        new EstateCityIdBox(
            $this->estatePost->getPost(),
            $this->viewLoader,
            $this->cityPost->getPost(),
            $this->metaPoly['city_id']
        );
    }
}
