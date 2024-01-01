<?php

namespace Vendor\UnderstrapEstate\Controller\Post;

use Vendor\UnderstrapEstate\Controller\MetaBox\CityDescriptionBox;
use Vendor\UnderstrapEstate\Entity\Post;
use Vendor\UnderstrapEstate\Entity\MetaPoly;
use Vendor\UnderstrapEstate\Entity\MetaPolyType;
use Vendor\UnderstrapEstate\Controller\BasePostController;

class CityPost extends BasePostController
{
    private Post $post;

    public function __construct()
    {
        $this->post = new Post(
            'city-estate',
            __('City', 'understrap-estate-plugin'),
            'dashicons-building',
            'manage_options',
            ['title', 'thumbnail']
        );

        $this->post->setPosition(2);
        $this->registerPublicType($this->post);

        $descriptionPoly = new MetaPoly(
            'city_description',
            MetaPolyType::STRING,
            __('Description', 'understrap-estate-plugin')
        );

        $this->addMetaPoly($this->post, $descriptionPoly);
        $descriptionBox = new CityDescriptionBox($this->post, $descriptionPoly);
    }

    public function getPost(): Post
    {
        return $this->post;
    }
}
