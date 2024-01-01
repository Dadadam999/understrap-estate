<table class="wp-list-table widefat fixed striped estate-table-metabox">
    <?php echo wp_nonce_field('city_id', 'city_id_nonce'); ?>
    <tr>
        <th scope="row" class="manage-column column-primary" style="width: 25%;"><?= $citiesField->renderLabel(); ?></th>
        <td style="width: 75%;"><?= $citiesField->renderField(); ?></td>
    </tr>
</table>