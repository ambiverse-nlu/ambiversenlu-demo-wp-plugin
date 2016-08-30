<?php
/**
 * Provides a settings view for the entity linking interface
 *
 *
 * @link       http://ambiverse.com
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
        <div class="form-group">
            <label class="col-sm-4 control-label">Coherence</label>
            <div class="col-sm-8">
            <label>
                <input type="checkbox" id="settings-coherent" <?php if($args['coherent-document']==true) {echo "checked='checked'";} ?>/> Coherent Text
            </label>
            </div>
        </div>
        <div class="form-group">
            <label for="settings-threshold" class="col-sm-4 control-label">Confidence</label>
            <div class="col-sm-8">
                <input id="settings-threshold"
                       data-slider-id='settings-threshold' type="text"
                       data-slider-min="0" data-slider-max="1" data-slider-step="0.1" data-slider-value="<?php echo $args["confidence-threshold"]; ?>"
                       style="width: 100%; padding-left: 15px;"/>
            </div>
        </div>
        <div class="form-group">
            <label for="settings-language" class="col-sm-4 control-label">Language</label>
            <div class="col-sm-8">
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