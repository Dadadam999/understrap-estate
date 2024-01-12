<?php

namespace UnderstrapEstate\Controller\Post;

use WpToolKit\Entity\Post;
use WpToolKit\Controller\PostController;

class CityPost extends PostController
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

        $this->post->position = 2;
        parent::__construct($this->post);
    }

    public function getPost(): Post
    {
        return $this->post;
    }
}
