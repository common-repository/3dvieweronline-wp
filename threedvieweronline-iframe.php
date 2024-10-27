<?php
/**
 * Plugin Name:       3D Viewer Online WordPress Plugin
 * Plugin URI:        https://www.3dvieweronline.com
 * Description:       An easy, realistic and customizable 3D Viewer to embed 3D models of your products/designs into your Wordpress/WooCommerce website (responsive layout)
 * Version:           2.2.2
 * Author:            Marco Da Lio @ 3DViewerOnline LTD
 * Author URI:        https://www.3dvieweronline.com
 * Text Domain:       threedvieweronline-iframe
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
defined('ABSPATH') || exit;

/**
 * Currently plugin version.
 */
define('THREED_VERSION', '2.2.2');

/**
 * Define THREED_PLUGIN_FILE.
 */
if (! defined('THREED_PLUGIN_FILE') ) {
    define('THREED_PLUGIN_FILE', __FILE__);
}

/**
 *  Define THREED_PLUGIN_DIR.
 */
if (! defined('THREED_PLUGIN_DIR') ) {
    $dir = dirname(__FILE__);
    define('THREED_PLUGIN_DIR', $dir);
}

/**
 *  Define WSCC_PLUGIN_BASENAME.
 */ 
if (! defined('THREED_PLUGIN_BASENAME') ) {
    define('THREED_PLUGIN_BASENAME', plugin_basename(__FILE__));
}

/**
 * The code that runs during plugin activation.
 */
function activate_threedvieweronline_iframe() 
{
    require_once THREED_PLUGIN_DIR . '/includes/class-threedvieweronline-iframe-activator.php';
    Threedvieweronline_Iframe_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_threedvieweronline_iframe() 
{
    require_once THREED_PLUGIN_DIR . '/includes/class-threedvieweronline-iframe-deactivator.php';
    Threedvieweronline_Iframe_Deactivator::deactivate();
}

register_activation_hook(THREED_PLUGIN_FILE, 'activate_threedvieweronline_iframe');
register_deactivation_hook(THREED_PLUGIN_FILE, 'deactivate_threedvieweronline_iframe');

/**
 * The core plugin class that is used to define internationalization,
 */
require THREED_PLUGIN_DIR . '/includes/class-threedvieweronline-iframe.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_threedvieweronline_iframe() 
{

    $plugin = new Threedvieweronline_Iframe();
    $plugin->run();

}
run_threedvieweronline_iframe();
