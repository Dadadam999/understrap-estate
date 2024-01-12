<?php

namespace UnderstrapEstate\Controller\MetaBox;

use WpToolKit\Entity\Post;
use WpToolKit\Entity\MetaPoly;
use WpToolKit\Controller\ViewLoader;
use WpToolKit\Interface\MetaBoxInterface;
use WpToolKit\Controller\MetaBoxController;

class CityListEstate extends MetaBoxController implements MetaBoxInterface
{
    public function __construct(
        private Post $post,
        private ViewLoader $viewLoader,
        private Post $estatePost,
        private MetaPoly $cityId
    ) {
        parent::__construct(
            'city_list_estate',
            __('List estate', 'understrap-estate-plugin'),
            $post->name
        );
    }

    public function render($post): void
    {
        $estates = $this->getEstates($post->ID);
        $totalEstates = count($estates);
        $estatesPerPage = 10;
        $totalPages = ceil($totalEstates / $estatesPerPage);
        $currentPage = get_query_var('paged') ? get_query_var('paged') : 1;
        $view = $this->viewLoader->getView('ListEstate');
        $view->addVariable('totalPages', $totalPages);
        $view->addVariable('currentPage', $currentPage);
        $view->addVariable('estates', $estates);
        $this->viewLoader->load($view->name);
    }

    public function callback($postId): void
    {
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $postId)) {
            return;
        }
    }

    private function getEstates(int $postId): array
    {
        $args = [
            'post_type' => 'estate',
            'posts_per_page' => -1,
            'meta_query' => [
                [
                    'key' => $this->cityId->name,
                    'value' => $postId,
                    'compare' => '=',
                ],
            ],
        ];

        $estates = new \WP_Query($args);

        if (!$estates->have_posts()) {
            return [];
        }

        $estatesList = [];

        while ($estates->have_posts()) {
            $estates->the_post();
            $estatesList[] = [
                'id' => get_the_ID(),
                'title' => get_the_title(),
                'description' => get_post_meta(get_the_ID(), 'estate_description', true),
                'image_url' => get_the_post_thumbnail_url(get_the_ID(), 'full'),
                'edit_link' => '/wp-admin/post.php?post=' . get_the_ID() . '&action=edit',
                'view_link' => get_permalink()
            ];
        }

        wp_reset_postdata();

        return $estatesList;
    }

    public function getPost(): Post
    {
        return $this->post;
    }
}
