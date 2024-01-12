<?php

namespace UnderstrapEstate\Controller\MetaBox;

use WpToolKit\Entity\Post;
use WpToolKit\Entity\MetaPoly;
use WpToolKit\Field\SelectField;
use WpToolKit\Controller\ViewLoader;
use WpToolKit\Interface\MetaBoxInterface;
use WpToolKit\Controller\MetaBoxController;

class EstateCityIdBox extends MetaBoxController implements MetaBoxInterface
{
    public function __construct(
        private Post $post,
        private ViewLoader $viewLoader,
        private Post $postCity,
        private MetaPoly $cityIdPoly
    ) {
        parent::__construct(
            'estate_city_id',
            __('ID city', 'understrap-estate-plugin'),
            $post->name
        );
    }

    public function render($post): void
    {
        $cities = get_posts([
            'post_type' => $this->postCity->name,
            'posts_per_page' => -1
        ]);

        $options = [];

        foreach ($cities as $city) {
            $options[$city->ID] = $city->post_title;
        }

        $citiesField = new SelectField(
            $this->cityIdPoly->name,
            __('Choose a real estate city.', 'understrap-estate-plugin'),
            $options,
            get_post_meta(
                $post->ID,
                $this->cityIdPoly->name,
                $this->cityIdPoly->single
            )
        );

        $view = $this->viewLoader->getView('EstateCityId');
        $view->addVariable('citiesField', $citiesField);
        $this->viewLoader->load($view->name);
    }

    public function callback($postId): void
    {
        if (isset($_POST['city_id_nonce']) && !wp_verify_nonce($_POST['city_id_nonce'], 'city_id')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $postId)) {
            return;
        }

        if (isset($_POST[$this->cityIdPoly->name])) {
            update_post_meta(
                $postId,
                $this->cityIdPoly->name,
                sanitize_text_field($_POST[$this->cityIdPoly->name])
            );
        }
    }

    public function getPost(): Post
    {
        return $this->post;
    }
}
