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

?><label class="imagebutton" for="<?php echo esc_attr( $atts['id'] ); ?>">
    <input
        <?php checked( $atts['selected'], $atts['value'], true ); ?>
           class="<?php echo esc_attr( $atts['class'] ); ?>"
           id="<?php echo esc_attr( $atts['id'] ); ?>"
           name="<?php echo esc_attr( $atts['name'] ); ?>"
           type="radio"
           value="<?php echo esc_attr( $atts['value'] ); ?>" />
    <img src="<?php echo esc_attr( $atts['img'] ); ?>" height="180px"/>
</label>