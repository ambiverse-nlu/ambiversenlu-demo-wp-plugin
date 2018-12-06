<?php
/**
 * Provide a pill output for the json results
 *
 *
 *
 * @link       https://ambiversenlu.mpi-inf.mpg.de
 * @since      0.9
 *
 * @package    Ambiverse_ELD
 * @subpackage Ambiverse_ELD/public/partials
 */
?>

<!-- Nav tabs -->
<ul class="nav nav-pills">
    <li role="presentation" class="active"><a href="#link" aria-controls="link" role="tab" data-toggle="tab"><i class="fa fa-lg fa-link"></i> Linking Output</a></li>
    <li role="presentation" ><a href="#meta" aria-controls="meta" role="tab" data-toggle="tab"><i class="fa fa-lg fa-tags"></i> Entity Metadata</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="link">
        <div id="ambiverse-json-linking-loader"></div>
        <?php do_action('ambiverse-eld-json-output'); ?>
    </div>
    <div role="tabpanel" class="tab-pane" id="meta">
        <div id="ambiverse-json-meta-loader"></div>
        <?php do_action('ambiverse-eld-json-output-meta'); ?>
    </div>

</div>