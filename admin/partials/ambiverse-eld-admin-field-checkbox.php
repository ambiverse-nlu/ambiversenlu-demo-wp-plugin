<?php
/**
 * Provides the markup for any checkbox field
 *
 * @link       https://ambiverse.com
 * @since      0.9
 *
 * @package    Ambiverse_ELD
 * @subpackage Ambiverse_ELD/admin/partials
 */
?><label for="<?php echo esc_attr( $atts['id'] ); ?>">
    <input aria-role="checkbox"
        <?php checked( 1, $atts['value'], true ); ?>
           class="<?php echo esc_attr( $atts['class'] ); ?>"
           id="<?php echo esc_attr( $atts['id'] ); ?>"
           name="<?php echo esc_attr( $atts['name'] ); ?>"
           type="checkbox"
           value="1" />
    <span class="description"><?php esc_html_e( $atts['description'], 'ambiverse-eld' ); ?></span>
</label>