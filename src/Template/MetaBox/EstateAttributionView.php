<table class="wp-list-table widefat fixed striped estate-table-metabox">
    <?php echo wp_nonce_field('estate_attribution', 'estate_attribution_nonce'); ?>

    <?php foreach ($fields as $field) : ?>
        <tr>
            <th scope="row" class="manage-column column-primary" style="width: 25%;"><?php echo $field->renderLabel(); ?></th>
            <td style="width: 75%;"><?php echo $field->renderField(); ?></td>
        </tr>
    <?php endforeach; ?>
</table>