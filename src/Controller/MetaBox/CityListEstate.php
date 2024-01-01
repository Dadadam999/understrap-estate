<?php

namespace Vendor\UnderstrapEstate\Controller\MetaBox;

use Vendor\UnderstrapEstate\Controller\BaseMetaBoxController;
use Vendor\UnderstrapEstate\Entity\MetaPoly;
use Vendor\UnderstrapEstate\Entity\Post;
use Vendor\UnderstrapEstate\Interface\MetaBoxInterface;

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

    /**
     * @var int $totalPages use in template
     * @var int $currentPage use in template
     */
    public function render($post): void
    {
        $estates = $this->getEstates($post->ID);
        $totalEstates = count($estates); // Общее количество 'estate'
        $estatesPerPage = 10; // Количество 'estate' на страницу
        $totalPages = ceil($totalEstates / $estatesPerPage);
        $currentPage = get_query_var('paged') ? get_query_var('paged') : 1;

        ob_start();

        require_once str_replace(
            '/',
            DIRECTORY_SEPARATOR,
            WP_PLUGIN_DIR . '/understrap-estate/src/Template/MetaBox/ListEstateView.php'
        );

        echo ob_get_clean();
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
