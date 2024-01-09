<?php

namespace Vendor\UnderstrapEstate\Controller\MetaBox;

use Vendor\UnderstrapEstate\Entity\Post;
use Vendor\UnderstrapEstate\Entity\MetaPoly;
use Vendor\UnderstrapEstate\Field\TextField;
use Vendor\UnderstrapEstate\Field\NumberField;
use Vendor\UnderstrapEstate\Entity\MetaPolyType;
use Vendor\UnderstrapEstate\Controller\ViewLoader;
use Vendor\UnderstrapEstate\Interface\FieldInterface;
use Vendor\UnderstrapEstate\Interface\MetaBoxInterface;
use Vendor\UnderstrapEstate\Controller\BaseMetaBoxController;

class EstateAttributionBox extends BaseMetaBoxController implements MetaBoxInterface
{
    /**
     * @param Post $post
     * @param MetaPoly[] $attributes
     */
    public function __construct(
        private Post $post,
        private array $attributes
    ) {
        parent::__construct(
            'estate_attributes',
            __('Estate attributes', 'understrap-estate-plugin'),
            $post->getName()
        );
    }

    public function render($post): void
    {
        $fields = [];

        foreach ($this->attributes as $attribute) {
            $fields[] = match ($attribute->getType()) {
                default => new TextField(
                    $attribute->getName(),
                    $attribute->getTitle(),
                    get_post_meta($post->ID, $attribute->getName(), $attribute->isSingle()),
                ),
                MetaPolyType::INTENGER => new NumberField(
                    $attribute->getName(),
                    $attribute->getTitle(),
                    get_post_meta($post->ID, $attribute->getName(), $attribute->isSingle()),
                )
            };
        }

        $view = ViewLoader::getView('EstateAttribution');
        $view->addVariable('fields', $fields);
        ViewLoader::load($view->name);
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
            if (isset($_POST[$attribut->getName()])) {
                update_post_meta(
                    $postId,
                    $attribut->getName(),
                    sanitize_text_field($_POST[$attribut->getName()])
                );
            }
        }
    }

    /**
     * @return MetaPoly[]
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    public function getPost(): Post
    {
        return $this->post;
    }
}
