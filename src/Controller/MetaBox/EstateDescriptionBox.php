<?php

namespace Vendor\UnderstrapEstate\Controller\MetaBox;

use Vendor\UnderstrapEstate\Entity\Post;
use Vendor\UnderstrapEstate\Field\TextAreaField;
use Vendor\UnderstrapEstate\Interface\MetaBoxInterface;
use Vendor\UnderstrapEstate\Controller\BaseMetaBoxController;
use Vendor\UnderstrapEstate\Entity\MetaPoly;

class EstateDescriptionBox extends BaseMetaBoxController implements MetaBoxInterface
{
    public function __construct(
        private Post $post,
        private MetaPoly $descriptionPoly

    ) {
        parent::__construct(
            'estate_description',
            __('Description', 'understrap-estate-plugin'),
            $post->getName()
        );
    }


    /**
     * @var TextAreaField $descriptionField use in template
     */
    public function render($post): void
    {
        $descriptionField = new TextAreaField(
            $this->descriptionPoly->getName(),
            __('Enter a description of the estate', 'understrap-estate-plugin'),
            get_post_meta(
                $post->ID,
                $this->descriptionPoly->getName(),
                $this->descriptionPoly->isSingle()
            )
        );

        ob_start();

        require_once str_replace(
            '/',
            DIRECTORY_SEPARATOR,
            WP_PLUGIN_DIR . '/understrap-estate/src/Template/MetaBox/EstateDescriptionView.php'
        );

        echo ob_get_clean();
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
