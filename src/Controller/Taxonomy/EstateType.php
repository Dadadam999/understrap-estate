<?php

namespace UnderstrapEstate\Controller\Taxonomy;

use WpToolKit\Entity\Post;
use WpToolKit\Entity\Taxonomy;
use WpToolKit\Controller\TaxonomyController;

class EstateType extends TaxonomyController
{
    private Taxonomy $taxonomy;

    public function __construct(private Post $post)
    {
        $this->taxonomy = new Taxonomy(
            'estatetypes',
            __('Types of estate', 'understrap-estate-plugin'),
            __('Type of estate', 'understrap-estate-plugin')
        );

        $this->taxonomy->showInQuickEdit = false;
        $this->taxonomy->showTagCloud = false;
        $this->taxonomy->hierarchical = true;
        parent::__construct($this->post, $this->taxonomy);
        $this->registerSubMenu();
    }

    public function getPost(): Post
    {
        return $this->post;
    }

    public function getTaxonomy(): Taxonomy
    {
        return $this->taxonomy;
    }
}
