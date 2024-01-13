<?php

namespace UnderstrapEstate\Controller\Post;

use WpToolKit\Entity\Post;
use WpToolKit\Controller\PostController;

class EstatePost extends PostController
{
    private Post $post;

    function __construct()
    {
        $this->post = new Post(
            'estate',
            __('Estate', 'understrap-estate-plugin'),
            'dashicons-admin-home',
            'manage_options',
            ['title', 'thumbnail']
        );

        parent::__construct($this->post);
        $this->addToMenu();
    }

    public function getPost(): Post
    {
        return $this->post;
    }
}
