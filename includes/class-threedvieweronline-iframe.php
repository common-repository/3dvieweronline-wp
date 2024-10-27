<?php
/**
 * 3D Viewer Online WordPress Plugin 
 * The core plugin class.
 * 
 * @since      1.0.0
 * @package    3DViewerOnline_WP_iframe
 * @subpackage 3DViewerOnline_WP_iframe/includes
 */
class Threedvieweronline_Iframe
{

    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      Threedvieweronline_Iframe_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $plugin_name    The string used to uniquely identify this plugin.
     */
    protected $plugin_name;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     * @access   protected
     * @var      string    $version    The current version of the plugin.
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct() 
    {
        if (defined('THREED_VERSION') ) {
            $this->version = THREED_VERSION;
        } else {
            $this->version = '1.0.0';
        }

        $this->plugin_name = 'threedvieweronline-iframe';

        $this->load_dependencies();
        $this->set_locale();
        $this->define_admin_hooks();
        $this->define_public_hooks();

    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Threedvieweronline_Iframe_Loader. Orchestrates the hooks of the plugin.
     * - Threedvieweronline_Iframe_i18n. Defines internationalization functionality.
     * - Threedvieweronline_Iframe_Admin. Defines all hooks for the admin area.
     * - Threedvieweronline_Iframe_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function load_dependencies() 
    {

        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once THREED_PLUGIN_DIR . '/includes/class-threedvieweronline-iframe-loader.php';

        /**
         * The class responsible for defining internationalization functionality
         * of the plugin.
         */
        require_once THREED_PLUGIN_DIR . '/includes/class-threedvieweronline-iframe-i18n.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once THREED_PLUGIN_DIR . '/admin/class-threedvieweronline-iframe-admin.php';

        /**
         * The class responsible for defining all actions that occur in the public-facing
         * side of the site.
         */
        require_once THREED_PLUGIN_DIR . '/public/class-threedvieweronline-iframe-public.php';

        $this->loader = new Threedvieweronline_Iframe_Loader();
        
        $this->loader->add_action('plugins_loaded', $this, 'check_for_woocommerce');
        
    }

    /**
     * Define the locale for this plugin for internationalization.
     *
     * Uses the Scratch_Coupon_Card_i18n class in order to set the domain and to register the hook
     * with WordPress.
     *
     * @since    1.0.0
     * @access   private
     */
    private function set_locale() 
    {

        $plugin_i18n = new Threedvieweronline_Iframe_i18n();

        $this->loader->add_action('plugins_loaded', $plugin_i18n, 'load_plugin_textdomain');

    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_admin_hooks() 
    {

        $plugin_admin = new Threedvieweronline_Iframe_Admin($this->get_plugin_name(), $this->get_version(), $this->get_loader());

        // Add common script to admin
        $this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'admin_enqueue_scripts' );
        
        //Plugin links
        $this->loader->add_filter('plugin_action_links_'.THREED_PLUGIN_BASENAME, $plugin_admin, 'plugin_settings_link');
        $this->loader->add_filter('plugin_row_meta', $plugin_admin, 'plugin_row_meta',10,2);
        
        //menu
        $this->loader->add_action('admin_menu', $plugin_admin, 'admin_menu');
        
        //block
        $this->loader->add_action('enqueue_block_assets', $plugin_admin, 'enqueue_block_assets');
        $this->loader->add_action('enqueue_block_editor_assets', $plugin_admin, 'enqueue_block_editor_assets');
        
        //add product meta boxes
        $this->loader->add_action('add_meta_boxes', $plugin_admin, 'threed_product_register_meta_boxes');
        $this->loader->add_action('save_post', $plugin_admin, 'threed_product_meta_box_save');
        
        //Tinymce Button
        $this->loader->add_action('admin_head', $plugin_admin, 'threed_add_my_custom_tinymce_button');
        
        
    }

    /**
     * Register all of the hooks related to the public-facing functionality
     * of the plugin.
     *
     * @since    1.0.0
     * @access   private
     */
    private function define_public_hooks() 
    {

        $plugin_public = new Threedvieweronline_Iframe_Public($this->get_plugin_name(), $this->get_version());
        
        $this->loader->add_action('init', $plugin_public, 'threedvieweronline_init_callback');
        
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_styles');
        $this->loader->add_action('wp_enqueue_scripts', $plugin_public, 'enqueue_scripts');
        $this->loader->add_action('wp_footer', $plugin_public, 'wp_footer_callback');
        
        $this->loader->add_action('woocommerce_product_tabs', $plugin_public, 'threed_woo_new_product_tab',100, 1);
        
        $this->loader->add_action('woocommerce_single_product_summary', $plugin_public, 'woocommerce_single_product_summary_callback',6);
        $this->loader->add_action('woocommerce_before_add_to_cart_form', $plugin_public, 'woocommerce_before_add_to_cart_form_callback',5);
        $this->loader->add_action('woocommerce_after_add_to_cart_form', $plugin_public, 'woocommerce_after_add_to_cart_form_callback',5);
        
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run() 
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     * @return    string    The name of the plugin.
     */
    public function get_plugin_name() 
    {
        return $this->plugin_name;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     * @return    Scratch_Coupon_Card_Loader    Orchestrates the hooks of the plugin.
     */
    public function get_loader() 
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     * @return    string    The version number of the plugin.
     */
    public function get_version() 
    {
        return $this->version;
    }
    
    /**
     * Check if WooCommerce is active.
     *
     * @since 1.0.0
     * @return string
     */
    public function check_for_woocommerce() 
    {
        if (!defined('WC_VERSION')) {
            add_action('admin_notices', array($this, 'woocommerce_wscc_missing_wc_notice' ));
        }
    }
    
    /**
     * WooCommerce fallback notice.
     *
     * @since 1.0.0
     * @return string
     */
    public function woocommerce_wscc_missing_wc_notice() 
    {
        
        echo '<div class="error"><p><strong>' . sprintf(esc_html__('3D Viewer Online WordPress Plugin')).'</strong>'.sprintf(esc_html__(' requires WooCommerce to be installed and active. You can download %s here.', 'THREED'), '<a href="https://woocommerce.com/" target="_blank">WooCommerce</a>') . '</strong></p></div>';
    }

}
