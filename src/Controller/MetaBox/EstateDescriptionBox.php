<?php

namespace UnderstrapEstate\Controller\MetaBox;

use WpToolKit\Entity\Post;
use WpToolKit\Entity\MetaPoly;
use WpToolKit\Field\TextAreaField;
use WpToolKit\Controller\ViewLoader;
use WpToolKit\Interface\MetaBoxInterface;
use WpToolKit\Controller\MetaBoxController;

class EstateDescriptionBox extends MetaBoxController implements MetaBoxInterface
{
    public function __construct(
        private Post $post,
        private ViewLoader $viewLoader,
        private MetaPoly $descriptionPoly
    ) {
        parent::__construct(
            'estate_description',
            __('Description', 'understrap-estate-plugin'),
            $post->name
        );
    }

    public function render($post): void
    {
        $descriptionField = new TextAreaField(
            $this->descriptionPoly->name,
            __('Enter a description of the estate', 'understrap-estate-plugin'),
            get_post_meta(
                $post->ID,
                $this->descriptionPoly->name,
                $this->descriptionPoly->single
            )
        );

        $view = $this->viewLoader->getView('EstateDescription');
        $view->addVariable('descriptionField', $descriptionField);
        $this->viewLoader->load($view->name);
    }

    public function callback($postId): void
    {
        if (isset($_POST['estate_description_nonce']) && !wp_verify_nonce($_POST['estate_description_nonce'], 'estate_description')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $postId)) {
            return;
        }

        if (isset($_POST[$this->descriptionPoly->name])) {
            update_post_meta(
                $postId,
                $this->descriptionPoly->name,
                esc_textarea($_POST[$this->descriptionPoly->name])
            );
        }
    }

    public function getPost(): Post
    {
        return $this->post;
    }
}
