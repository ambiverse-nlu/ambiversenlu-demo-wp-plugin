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
<p>Please enter the OAuth2 authentication settings in order to use the plugin.
    The plugin needs this to call the <a href="https://www.ambiverse.com/natural-language-understanding-api/" target="_blank">Ambiverse NLU API</a>.
    If you don't have credentials, register <a href="https://developer.ambiverse.com/signup" target="_blank">here</a>.
</p>
<p>The url used for executing the OAuth 2 client credential flow is <a href="<?php echo esc_attr( $atts['api-oauth-url'] ); ?>"><?php echo esc_attr( $atts['api-oauth-url'] ); ?></a></p>