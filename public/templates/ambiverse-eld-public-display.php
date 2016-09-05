<?php
/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       http://ambiverse.com
 * @since      0.9
 *
 * @package    Ambiverse_ELD
 * @subpackage Ambiverse_ELD/public/partials
 */

//do_action('ambiverse-eld-before-content');

?>
<div class="ambiverse-eld-wrap">
<!--    <h2>--><?php //echo esc_html_e('Entity Linking Demo', 'ambiverse-eld'); ?><!--</h2>-->
    <div class="row">
    <div class="col-md-9 form-group" style="padding-left: 15px;">
        <?php do_action('ambiverse-eld-content', $args); ?>
        <span id="helpBlock" class="help-block"><small><?php esc_html_e('Enter any text in ', 'ambiverse-eld'); ?><?php echo $args['supported-languages']; ?></small></span>
    </div>
    <div class="col-md-3 form-group">
        <?php do_action('ambiverse-eld-public-settings', $args); ?>
    </div>
    </div>
    <?php do_action('ambiverse-eld-button'); ?>

    <?php //do_action('ambiverse-eld-after-content'); ?>

    <div id="result-wrapper" style="display: none;">
<!--        <h2>--><?php //echo esc_html_e('Result', 'ambiverse-eld'); ?><!--</h2>-->
        <div>&nbsp;</div>
        <div id="loading">
            <?php do_action('ambiverse-eld-public-tab'); ?>
        </div>
    </div>
</div>