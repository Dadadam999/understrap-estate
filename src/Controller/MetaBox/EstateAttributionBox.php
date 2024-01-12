<?php

namespace UnderstrapEstate\Controller\MetaBox;

use WpToolKit\Entity\Post;
use WpToolKit\Entity\MetaPoly;
use WpToolKit\Field\TextField;
use WpToolKit\Field\NumberField;
use WpToolKit\Entity\MetaPolyType;
use WpToolKit\Controller\ViewLoader;
use WpToolKit\Interface\MetaBoxInterface;
use WpToolKit\Controller\MetaBoxController;

class EstateAttributionBox extends MetaBoxController implements MetaBoxInterface
{
    /**
     * @param Post $post
     * @param MetaPoly[] $attributes
     */
    public function __construct(
        private Post $post,
        private ViewLoader $viewLoader,
        public array $attributes
    ) {
        parent::__construct(
            'estate_attributes',
            __('Estate attributes', 'understrap-estate-plugin'),
            $post->name
        );
    }

    public function render($post): void
    {
        $fields = [];

        foreach ($this->attributes as $attribute) {
            $fields[] = match ($attribute->type) {
                default => new TextField(
                    $attribute->name,
                    $attribute->title,
                    get_post_meta($post->ID, $attribute->name, $attribute->single),
                ),
                MetaPolyType::INTENGER => new NumberField(
                    $attribute->name,
                    $attribute->title,
                    get_post_meta($post->ID, $attribute->name, $attribute->single),
                )
            };
        }

        $view = $this->viewLoader->getView('EstateAttribution');
        $view->addVariable('fields', $fields);
        $this->viewLoader->load($view->name);
    }

    public function callback($postId): void
    {
        if (isset($_POST['estate_attribution_nonce']) && !wp_verify_nonce($_POST['estate_attribution_nonce'], 'estate_attribution')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        if (!current_user_can('edit_post', $postId)) {
            return;
        }

        foreach ($this->attributes as $attribut) {
            if (isset($_POST[$attribut->name])) {
                update_post_meta(
                    $postId,
                    $attribut->name,
                    sanitize_text_field($_POST[$attribut->name])
                );
            }
        }
    }

    public function getPost(): Post
    {
        return $this->post;
    }
}
