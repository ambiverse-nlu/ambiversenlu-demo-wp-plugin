<?php
///**
// * Provides the markup for any text field
// *
// * @link       https://ambiverse.com
// * @since      0.9
// *
// * @package    Ambiverse_ELD
// * @subpackage Ambiverse_ELD/admin/partials
// */
?>
<div id="<?php echo esc_attr( $atts['id'] ); ?>-slider" style="width: 250px;"></div>

Value: <input type="text" name="<?php echo esc_attr( $atts['name'] ); ?>" class="description" id="<?php echo esc_attr( $atts['id'] ); ?>"
       value="<?php echo esc_attr( $atts['value'] ); ?>" readonly style="border:0; font-weight:bold;"/>

    <script>
    (function( $ ) {
        $( function() {
            $( "#<?php echo esc_attr( $atts['id'] ); ?>-slider" ).slider({
                range: "min",
                value: <?php echo esc_attr( $atts['value'] ); ?>,
                min: <?php echo esc_attr( $atts['slider-min'] ); ?>,
                max: <?php echo esc_attr( $atts['slider-max'] ); ?>,
                step: <?php echo esc_attr( $atts['slider-step'] ); ?>,
                slide: function( event, ui ) {
                    $( "#<?php echo esc_attr( $atts['id'] ); ?>" ).val(ui.value );
                }
            });
            $( "#<?php echo esc_attr( $atts['id'] ); ?>" ).val($( "#<?php echo esc_attr( $atts['id'] ); ?>-slider" ).slider( "value" ) );
        } );
        })( jQuery );
</script>