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
    maxlength="<?php echo esc_attr($args['max-length']); ?>"
    data-coherent-document="<?php echo esc_attr($args['coherent-document']); ?>"
    data-concept="<?php echo esc_attr($args['concept']); ?>"
    data-confidence-threshold="<?php echo esc_attr($args['confidence-threshold']); ?>"
    data-entity-layout="<?php echo esc_attr($args['entity-layout']); ?>"
    data-entity-images="<?php echo esc_attr($args['entity-images']); ?>"
    data-entity-icons="<?php echo esc_attr($args['entity-icons']); ?>"
    data-entity-free-images="<?php echo esc_attr($args['entity-free-images']); ?>"
    ><?php
    echo esc_textarea($args['text']); ?></textarea>
