<?php
/**
 * Provide a TAB output for the results
 *
 *
 *
 * @link       http://ambiverse.com
 * @since      0.9
 *
 * @package    Ambiverse_ELD
 * @subpackage Ambiverse_ELD/public/partials
 */
?>

<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#entities" aria-controls="entities" role="tab" data-toggle="tab"><i class="fa fa-lg fa-eye"></i> Visual</a></li>
    <li role="presentation" ><a href="#json" aria-controls="json" role="tab" data-toggle="tab"><i class="fa fa-lg fa-code"></i> JSON</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="entities">
        <div>&nbsp;</div>
        <?php do_action('ambiverse-eld-public-text-result'); ?>


        <?php do_action('ambiverse-eld-public-entities'); ?>

        <?php do_action('ambiverse-eld-open-facts'); ?>
    </div>
    <div role="tabpanel" class="tab-pane" id="json" style="padding-top: 10px;">
        <?php do_action('ambiverse-eld-public-pills'); ?>
    </div>

</div>