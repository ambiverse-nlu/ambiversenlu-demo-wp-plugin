<?php
/**
 * Provides a settings view for the entity linking interface
 *
 *
 * @link       https://ambiversenlu.mpi-inf.mpg.de
 * @since      0.9
 *
 * @package    Ambiverse_ELD
 * @subpackage Ambiverse_ELD/public/partials
 */

?>
<div class="panel panel-default settings">
<h4 style="padding-left: 20px;"><i class="fa fa-cog"></i> Settings</h4>
    <div class="panel-body">
        <form class="form-horizontal">
<!--        <div class="form-group">-->
<!--            <label class="col-sm-5 control-label">Coherence</label>-->
<!--            <div class="col-sm-7">-->
<!--            <label>-->
<!--                <input type="checkbox" id="settings-coherent" --><?php //if($args['coherent-document']==true) {echo "checked='checked'";} ?><!--/> Coherent Text-->
<!--            </label>-->
<!--            </div>-->
<!--        </div>-->
         <input type="hidden" id="api-endpoint" name="api-endpoint" value="<?php echo $args["settings-api-endpoint"]; ?>" />
         <input type="hidden" id="api-method" name="api-method" value="<?php echo $args["settings-api-method"]; ?>" />
        <div class="form-group">
            <label for="settings-threshold" class="col-sm-5 control-label">Confidence threshold</label>
            <div class="col-sm-7" style="padding-top: 10px;">
               <div class="row">
                   <div class="col-xs-9">
                <input id="settings-threshold"
                       data-slider-id='settings-threshold' type="text"
                       data-slider-min="0" data-slider-max="1" data-slider-step="0.005" data-slider-value="<?php echo $args["confidence-threshold"]; ?>"
                       style="width: 100%;"/>
               </div>
               <div class="col-xs-3" style="padding: 0px;">
                   <small><span id="threshold-val"><?php echo $args["confidence-threshold"]; ?></span></small>
               </div>
               </div>
            </div>
        </div>
        <div class="form-group">
            <label for="settings-language" class="col-sm-5 control-label">Language</label>
            <div class="col-sm-7">
            <select id="settings-language" class="form-control">
                <option value="auto">Auto</option>
                <?php
                    foreach ( $args['languages'] as $selection ) {
                            if ( is_array( $selection ) ) {
                                $label = $selection['label'];
                                $value = $selection['value'];
                            } else {
                                $label = strtolower( $selection );
                                $value = strtolower( $selection );
                            }
                            ?><option
                            value="<?php echo esc_attr( $value ); ?>" ><?php
                            esc_html_e( $label, 'ambiverse-eld' );
                            ?></option><?php
                        } // foreach
                ?>
            </select>
            </div>
        </div>
        </form>
    </div>
</div>