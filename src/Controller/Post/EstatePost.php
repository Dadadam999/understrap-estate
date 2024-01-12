<?php

namespace UnderstrapEstate\Controller\Post;

use WpToolKit\Entity\Post;
use WpToolKit\Entity\MetaPoly;
use WpToolKit\Entity\Taxonomy;
use WpToolKit\Entity\MetaPolyType;
use WpToolKit\Controller\BasePostController;
use UnderstrapEstate\Controller\Taxonomy\EstateType;
use UnderstrapEstate\Controller\MetaBox\EstateAttributionBox;
use UnderstrapEstate\Controller\MetaBox\EstateDescriptionBox;

class EstatePost extends BasePostController
{
    private Post $post;
    private EstateType $estateType;

    private EstateAttributionBox $attributionBox;

    function __construct()
    {
        $this->post = new Post(
            'estate',
            __('Estate', 'understrap-estate-plugin'),
            'dashicons-admin-home',
            'manage_options',
            ['title', 'thumbnail']
        );

        $this->registerPublicType($this->post);
        $this->registerMenu($this->post);
        $this->estateType = new EstateType($this->post);

        $descriptionPoly = new MetaPoly(
            'estate_description',
            MetaPolyType::STRING,
            __('Description', 'understrap-estate-plugin')
        );

        $descriptionBox = new EstateDescriptionBox($this->post, $descriptionPoly);

        $metaPolies = [
            new MetaPoly(
                'estate_address',
                MetaPolyType::STRING,
                __('Address', 'understrap-estate-plugin')
            ),
            new MetaPoly(
                'estate_area',
                MetaPolyType::INTENGER,
                __('Area')
            ),
            new MetaPoly(
                'estate_living_area',
                MetaPolyType::INTENGER,
                __('Living area', 'understrap-estate-plugin')
            ),
            new MetaPoly(
                'estate_floor',
                MetaPolyType::INTENGER,
                __('Floor', 'understrap-estate-plugin')
            ),
            new MetaPoly(
                'estate_cost',
                MetaPolyType::INTENGER,
                __('Cost', 'understrap-estate-plugin')
            ),
        ];

        foreach ($metaPolies as $metapoly) {
            $this->addMetaPoly($this->post, $metapoly);
        }

        $this->attributionBox = new EstateAttributionBox($this->post, $metaPolies);
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function getEstateType(): Taxonomy
    {
        return $this->estateType->getTaxonomy();
    }

    public function getEstateAttributionBox(): EstateAttributionBox
    {
        return $this->attributionBox;
    }
}
