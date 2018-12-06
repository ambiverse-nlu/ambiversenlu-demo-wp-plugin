<?php
/**
 * Provides the markup for a select field
 *
 * @link       https://ambiversenlu.mpi-inf.mpg.de
 * @since      0.9
 *
 * @package    Ambiverse_ELD
 * @subpackage Ambiverse_ELD/admin/partials
 */
if ( ! empty( $atts['label'] ) ) {
    ?><label for="<?php echo esc_attr( $atts['id'] ); ?>"><?php esc_html_e( $atts['label'], 'ambiverse-eld' ); ?>: </label><?php
}
?><select
    aria-label="<?php esc_attr( _e( $atts['aria'], 'ambiverse-eld' ) ); ?>"
    class="<?php echo esc_attr( $atts['class'] ); ?>"
    id="<?php echo esc_attr( $atts['id'] ); ?>"
    name="<?php echo esc_attr( $atts['name'] ); ?>"><?php
    if ( ! empty( $atts['blank'] ) ) {
        ?><option value><?php esc_html_e( $atts['blank'], 'ambiverse-eld' ); ?></option><?php
    }
    foreach ( $atts['selections'] as $selection ) {
        if ( is_array( $selection ) ) {
            $label = $selection['label'];
            $value = $selection['value'];
        } else {
            $label = strtolower( $selection );
            $value = strtolower( $selection );
        }
        ?><option
        value="<?php echo esc_attr( $value ); ?>" <?php
        selected( $atts['value'], $value ); ?>><?php
        esc_html_e( $label, 'ambiverse-eld' );
        ?></option><?php
    } // foreach
    ?></select>
<span class="description"><?php esc_html_e( $atts['description'], 'ambiverse-eld' ); ?></span>
</label>