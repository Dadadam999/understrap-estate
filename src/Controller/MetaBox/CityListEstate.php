<?php

namespace Vendor\UnderstrapEstate\Controller\MetaBox;

use Vendor\UnderstrapEstate\Entity\Post;
use Vendor\UnderstrapEstate\Entity\MetaPoly;
use Vendor\UnderstrapEstate\Controller\ViewLoader;
use Vendor\UnderstrapEstate\Interface\MetaBoxInterface;
use Vendor\UnderstrapEstate\Controller\BaseMetaBoxController;

class CityListEstate extends BaseMetaBoxController implements MetaBoxInterface
{
    public function __construct(
        private Post $post,
        private Post $estatePost,
        private MetaPoly $cityId
    ) {
        parent::__construct(
            'city_list_estate',
            __('List estate', 'understrap-estate-plugin'),
            $post->getName()
        );
    }

    public function render($post): void
    {
        $estates = $this->getEstates($post->ID);
        $totalEstates = count($estates); // Общее количество 'estate'
        $estatesPerPage = 10; // Количество 'estate' на страницу
        $totalPages = ceil($totalEstates / $estatesPerPage);
        $currentPage = get_query_var('paged') ? get_query_var('paged') : 1;
        $view = ViewLoader::getView('ListEstate');
        $view->addVariable('totalPages', $totalPages);
        $view->addVariable('currentPage', $currentPage);
        $view->addVariable('estates', $estates);
        ViewLoader::load($view->name);
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
                    'key' => $this->cityId->getName(),
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
