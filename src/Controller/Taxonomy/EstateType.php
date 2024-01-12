<?php

namespace UnderstrapEstate\Controller\Taxonomy;

use WpToolKit\Entity\Post;
use WpToolKit\Entity\Taxonomy;
use WpToolKit\Controller\BaseTaxonomyController;

class EstateType extends BaseTaxonomyController
{
    private Taxonomy $taxonomy;

    public function __construct(private Post $post)
    {
        $this->taxonomy = new Taxonomy(
            'estatetypes',
            __('Types of estate', 'understrap-estate-plugin'),
            __('Type of estate', 'understrap-estate-plugin')
        );

        $this->taxonomy->setShowInQuickEdit(false);
        $this->taxonomy->setShowTagCloud(false);
        $this->taxonomy->setHierarchical(true);
        $this->registerTaxonomy($this->post, $this->taxonomy);
        $this->registerSebMenu($this->post, $this->taxonomy);
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
