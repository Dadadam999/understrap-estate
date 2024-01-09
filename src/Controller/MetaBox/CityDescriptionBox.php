<?php

namespace Vendor\UnderstrapEstate\Controller\MetaBox;

use Vendor\UnderstrapEstate\Entity\Post;
use Vendor\UnderstrapEstate\Field\TextAreaField;
use Vendor\UnderstrapEstate\Interface\MetaBoxInterface;
use Vendor\UnderstrapEstate\Controller\BaseMetaBoxController;
use Vendor\UnderstrapEstate\Controller\ViewLoader;
use Vendor\UnderstrapEstate\Entity\MetaPoly;

class CityDescriptionBox extends BaseMetaBoxController implements MetaBoxInterface
{
    public function __construct(
        private Post $post,
        private MetaPoly $descriptionPoly

    ) {
        parent::__construct(
            'city_description',
            __('Description', 'understrap-estate-plugin'),
            $post->getName()
        );
    }

    public function render($post): void
    {
        $descriptionField = new TextAreaField(
            $this->descriptionPoly->getName(),
            __('Enter a description of the city', 'understrap-estate-plugin'),
            get_post_meta(
                $post->ID,
                $this->descriptionPoly->getName(),
                $this->descriptionPoly->isSingle()
            )
        );

        $view = ViewLoader::getView('CityDescription');
        $view->addVariable('descriptionField', $descriptionField);
        ViewLoader::load($view->name);
    }

    public function callback($postId): void
    {
        if (isset($_POST['city_description_nonce']) && !wp_verify_nonce($_POST['city_description_nonce'], 'city_description')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $postId)) {
            return;
        }

        if (isset($_POST[$this->descriptionPoly->getName()])) {
            update_post_meta(
                $postId,
                $this->descriptionPoly->getName(),
                esc_textarea($_POST[$this->descriptionPoly->getName()])
            );
        }
    }

    public function getPost(): Post
    {
        return $this->post;
    }
}
