<?php
/**
 * 3D Viewer Online WordPress Plugin
 * Define the internationalization functionality 
 * so that it is ready for translation.
 * 
 * @since      1.0.0
 * @package    3DViewerOnline_WP_iframe
 * @subpackage 3DViewerOnline_WP_iframe/includes
 */

class Threedvieweronline_Iframe_i18n
{


    /**
     * Load the plugin text domain for translation.
     *
     * @since    1.0.0
     */
    public function load_plugin_textdomain() 
    {

        load_plugin_textdomain(
            'threedvieweronline-iframe',
            false,
            dirname(dirname(plugin_basename(__FILE__))) . '/languages/'
        );

    }



}
