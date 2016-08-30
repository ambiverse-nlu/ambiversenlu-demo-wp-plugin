<?php
/**
 * Text area for the
 *
 *
 * @link       http://ambiverse.com
 * @since      0.9
 *
 * @package    Ambiverse_ELD
 * @subpackage Ambiverse_ELD/public/partials
 */
?>

<textarea
    class="<?php echo esc_attr($args['class']); ?>"
    cols="<?php echo esc_attr($args['cols']); ?>"
    id="<?php echo esc_attr($args['id']); ?>"
    name="<?php echo esc_attr($args['name']); ?>"
    rows="<?php echo esc_attr($args['rows']); ?>"
    data-coherent-document="<?php echo esc_attr($args['coherent-document']); ?>"
    data-confidence-threshold="<?php echo esc_attr($args['confidence-threshold']); ?>"><?php
    echo esc_textarea($args['text']); ?></textarea>
