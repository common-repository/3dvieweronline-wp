<?php
/**
 * 3D Viewer Online WordPress Plugin
 * Fired during plugin activation
 * 
 * @since      1.0.0
 * @package    3DViewerOnline_WP_iframe
 * @subpackage 3DViewerOnline_WP_iframe/includes
 */

class Threedvieweronline_Iframe_Activator
{
    
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     *
     */
    public function __construct() 
    {

        $this->load_dependencies();

    }
    
    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Scratch_Coupon_Card_Default.
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() 
    {

        /**
         * The class responsible for defining default value
         * of the plugin.
         */
        
    }
    
    public static function activate() 
    {
        
        $settings=array();
        
        $settings['threed_enable_product_tab']='off';
        $settings['threed_tab_title']='3D model';
        $settings['threed_enable_product_icon']='on';
        $settings['threed_icon_display']='after-product-title';
        $settings['threed_icon']=plugins_url('assets/images/logo.png', THREED_PLUGIN_FILE);
        $settings['threed_iframe_width']='100%';
        $settings['threed_iframe_height']='500px';
        
        update_option("settings-threed-iframe", $settings);
    }

}
