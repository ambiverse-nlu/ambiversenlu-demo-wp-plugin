<?php
/**
 * Provide a view for a section
 *
 * Enter text below to appear below the section title on the Settings page
 *
 * @link       https://ambiversenlu.mpi-inf.mpg.de
 * @since      0.9
 *
 * @package    Ambiverse_ELD
 * @subpackage Ambiverse_ELD/admin/partials
 */
?><p>Configure the disambiguation settings, like supported languages, default threshold and coherent document settings.</p>
<p>The text analysis calls will be made to this API: <a href="<?php echo $atts['entity-linking-url']; ?>"><?php echo $atts['entity-linking-url']; ?></a>,
    and more information about the entities will be retrieved from this API: <a href="<?php echo $atts['knowledge-graph-url']; ?>"><?php echo $atts['knowledge-graph-url']; ?></a></p>