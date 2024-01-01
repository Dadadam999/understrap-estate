<div class="city-description-metabox">
    <?= wp_nonce_field('estate_description', 'estate_description_nonce'); ?>
    <?= $descriptionField->renderLabel(); ?>
    <?= $descriptionField->renderField(); ?>
</div>