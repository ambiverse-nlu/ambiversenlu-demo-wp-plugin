<?php
/**
 * Provide a view for a section
 *
 * Enter text below to appear below the section title on the Settings page
 *
 * @link       http://ambiverse.com
 * @since      0.9
 *
 * @package    Ambiverse_ELD
 * @subpackage Ambiverse_ELD/admin/partials
 */
?>

<p class="description">
    <label for="wpcf7-shortcode">Copy this shortcode and paste it into your post, page, or text widget content:</label>
    <span class="shortcode wp-ui-highlight"><input type="text" id="wpcf7-shortcode" onfocus="this.select();" readonly="readonly" class="large-text code"
                                                   value="[ambiverse-eld coherent-document=&quot;<?php echo esc_attr( $atts['settings-coherent-document'] ); ?>&quot; confidence-threshold=<?php echo esc_attr( $atts['settings-threshold-document'] ); ?> language=&quot;en&quot; settings-api-endpoint=&quot;<?php echo esc_attr( $atts['settings-api-endpoint'] ); ?>&quot; settings-api-method=&quot;<?php echo esc_attr( $atts['settings-api-method'] ); ?>&quot;]When [[Who]] played Tommy in Columbus, Pete was at his best.[/ambiverse-eld]"></span>
</p>
