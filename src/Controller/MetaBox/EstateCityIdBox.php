<?php

namespace Vendor\UnderstrapEstate\Controller\MetaBox;

use Vendor\UnderstrapEstate\Entity\Post;
use Vendor\UnderstrapEstate\Entity\MetaPoly;
use Vendor\UnderstrapEstate\Field\SelectField;
use Vendor\UnderstrapEstate\Interface\MetaBoxInterface;
use Vendor\UnderstrapEstate\Controller\BaseMetaBoxController;

class EstateCityIdBox extends BaseMetaBoxController implements MetaBoxInterface
{
    public function __construct(
        private Post $post,
        private Post $postCity,
        private MetaPoly $cityIdPoly
    ) {
        parent::__construct(
            'estate_city_id',
            __('ID city', 'understrap-estate-plugin'),
            $post->getName()
        );
    }

    /**
     * @var SelectField $citiesField use in template
     */
    public function render($post): void
    {
        $cities = get_posts([
            'post_type' => $this->postCity->getName(),
            'posts_per_page' => -1
        ]);

        $options = [];

        foreach ($cities as $city) {
            $options[$city->ID] = $city->post_title;
        }

        $citiesField = new SelectField(
            $this->cityIdPoly->getName(),
            __('Choose a real estate city.', 'understrap-estate-plugin'),
            $options,
            get_post_meta(
                $post->ID,
                $this->cityIdPoly->getName(),
                $this->cityIdPoly->isSingle()
            )
        );

        ob_start();

        require_once str_replace(
            '/',
            DIRECTORY_SEPARATOR,
            WP_PLUGIN_DIR . '/understrap-estate/src/Template/MetaBox/EstateCityIdView.php'
        );

        echo ob_get_clean();
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

        if (isset($_POST[$this->cityIdPoly->getName()])) {
            update_post_meta(
                $postId,
                $this->cityIdPoly->getName(),
                sanitize_text_field($_POST[$this->cityIdPoly->getName()])
            );
        }
    }

    public function getPost(): Post
    {
        return $this->post;
    }
}