<?php
/**
 * 3D Viewer Online WordPress Plugin
 * Fired when the plugin is uninstalled.
 *
 * @since      2.2.1
 * @package    3DViewerOnline_WP_iframe
 */

// If uninstall not called from WordPress, then exit.
if (! defined('WP_UNINSTALL_PLUGIN') ) {
    exit;
}

global $wpdb;

delete_option("settings-threed-iframe");
