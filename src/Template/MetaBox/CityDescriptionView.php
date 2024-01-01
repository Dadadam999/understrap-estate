<div class="city-description-metabox">
    <?= wp_nonce_field('city_description', 'city_description_nonce'); ?>
    <?= $descriptionField->renderLabel(); ?>
    <?= $descriptionField->renderField(); ?>
</div>