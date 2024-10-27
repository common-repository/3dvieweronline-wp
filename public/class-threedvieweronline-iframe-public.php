<?php
/**
 * 3D Viewer Online WordPress Plugin
 * The public-facing functionality of the plugin.
 * 
 * @since      1.0.0
 * @package    3DViewerOnline_WP_iframe
 * @subpackage 3DViewerOnline_WP_iframe/public
 */

class Threedvieweronline_Iframe_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;
    
    
    /**
     * The settings of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      array    $settings The settings of this plugin.
     */
    private $settings = array();
    
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version ) 
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        
        $this->settings=$this->getSettings();
        $this->load_dependencies();
    }
    
    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Woo_Scratch_Coupon_Card_Default.
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
    
    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() 
    {
        wp_enqueue_style($this->plugin_name, plugins_url('/assets/fancybox/jquery.fancybox.min.css', THREED_PLUGIN_FILE), array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() 
    {
        wp_enqueue_script($this->plugin_name.'-fancybox', plugins_url('/assets/fancybox/jquery.fancybox.min.js', THREED_PLUGIN_FILE), array( 'jquery' ), $this->version, false);
        
    }
    
    public function wp_footer_callback() 
    {
        ?>
            <script type="text/javascript">
              if ( undefined !== window.jQuery ) {
                jQuery(document).ready(function() {
                    //jQuery('.fancybox').fancybox();
                    jQuery('.fancybox').fancybox({
                        toolbar  : false,
                        smallBtn : true,
                        iframe : {
                            preload : true
                        }
                    })
                });
              }
            </script>
            <style>
                .fancybox-close-small{
                    opacity: 1;
                    color: #fff;
                    font-weight: bolder;
                }
                .fancybox-close-small:hover{
                    color: #000;
                    opacity: 1;
                }
            </style>
            
        <?php 
        
    }
    
    /**
     * Retrieve the settings of the plugin.
     *
     * @since     1.0.0
     * @return    array    The settings of the plugin.
     */
    public function getSettings() 
    {
        return $this->settings=get_option('settings-threed-iframe');
    }
    
    /**
     * Initialize threedvieweronline.
     *
     * @since     1.0.0
     * @return    html.
     */
    public function threedvieweronline_init_callback() 
    {
        add_shortcode( '3Dvo-model', array($this, 'threedvieweronline_shortcode' ));
    }    
    
    public function threedvieweronline_shortcode($atts) 
    {
        if( !is_array( $atts ) ) { return ''; }
        
        $atts = shortcode_atts( array(
            'url' => '#',
            'id' => '3Dvo-model',
            'width' => '100%',
            'height' => '500px',
            'autosize' => true,
            'border' => '0',
            'scroll' => 'no',
        ), $atts, 'THREED' );
        
        if (!filter_var($atts['url'], FILTER_VALIDATE_URL)) {
            return '';
        } 
        
        return '<iframe id="' . $atts['id'] . '" src="' . $atts['url'] . '" width="' . $atts['width'] . '" height="' . $atts['height'] . '" frameborder="' . $atts['border'] . '" scrolling="' . $atts['scroll'] .'" allowfullscreen webkitallowfullscreen mozallowfullscreen></iframe>';
        
    }    
    /**
     * Initialize product tab.
     *
     * @since     1.0.0
     * @return    html.
     */
    public function threed_woo_new_product_tab($tabs) 
    {
        if(isset($this->settings['threed_enable_product_tab']) && $this->settings['threed_enable_product_tab']=='on') {
            
            $title='3D model';
            if(isset($this->settings['threed_tab_title']) && $this->settings['threed_tab_title']!=''){
                $title=$this->settings['threed_tab_title'];
            }
            
            global $product;
            $id = $product->get_id();
            $value = get_post_meta( $id, 'threed_model_url',true);
            if($value !=''){
                $tabs['desc_tab'] = array(
                    'title'     => __( $title, 'woocommerce' ),
                    'priority'  => 50,
                    'callback'  => array($this,'threed_woo_new_product_tab_content')
                );
            }
        }
        return $tabs;
        
    } 
    
    public function threed_woo_new_product_tab_content() 
    {
        global $product;
           
        $id = $product->get_id();
        $url = get_post_meta( $id, 'threed_model_url',true);
        $pwidth='';
        $pheight='';
        
        $pwidth = get_post_meta( $id, 'threed_piframe_width',true);
        $pheight = get_post_meta( $id, 'threed_piframe_height',true);
        
        if($pwidth==''){
            if(isset($this->settings['threed_iframe_width']) && $this->settings['threed_iframe_width']!='') {
                $pwidth = $this->settings['threed_iframe_width'];
            }else{
                $pwidth ="100%";
            }
        }
        
        if($pheight==''){
            if(isset($this->settings['threed_iframe_height']) && $this->settings['threed_iframe_height']!='') {
                $pheight = $this->settings['threed_iframe_height'];
            }else{
                $pheight ="500px";
            }
        }
        
        if($url!=''){
            echo '<iframe class="threed_model_iframe" width="'.$pwidth.'" height="'.$pheight.'" src="'.$url.'" frameborder="0" allowfullscreen=""></iframe>';
        }
    }
    
    public function woocommerce_single_product_summary_callback() 
    {
        if(isset($this->settings['threed_icon_display']) && $this->settings['threed_icon_display']=='after-product-title') {
            $this->add_threed_icon();
        }
    }
    public function woocommerce_before_add_to_cart_form_callback() 
    {
        if(isset($this->settings['threed_icon_display']) && $this->settings['threed_icon_display']=='after-product-short-description') {
            $this->add_threed_icon();
        }
    }
    public function woocommerce_after_add_to_cart_form_callback() 
    {
        if(isset($this->settings['threed_icon_display']) && $this->settings['threed_icon_display']=='after-add-to-cart-button') {
            $this->add_threed_icon();
        }
    }
    public function add_threed_icon() 
    {
        if(isset($this->settings['threed_enable_product_icon']) && $this->settings['threed_enable_product_icon']=='on') {
            $icon=plugins_url('assets/images/logo.png', THREED_PLUGIN_FILE);
            if(isset($this->settings['threed_icon']) && $this->settings['threed_icon']!=''){
                $icon=$this->settings['threed_icon'];
            }
            
            global $product;
            $id = $product->get_id();
            $url = get_post_meta( $id, 'threed_model_url',true);
            
            $pwidth='';
            $pheight='';
            
            $pwidth = get_post_meta( $id, 'threed_piframe_width',true);
            $pheight = get_post_meta( $id, 'threed_piframe_height',true);
            
            if($pwidth==''){
                if(isset($this->settings['threed_iframe_width']) && $this->settings['threed_iframe_width']!='') {
                    $pwidth = $this->settings['threed_iframe_width'];
                }else{
                    $pwidth ="100%";
                }
            }
            
            if($pheight==''){
                if(isset($this->settings['threed_iframe_height']) && $this->settings['threed_iframe_height']!='') {
                    $pheight = $this->settings['threed_iframe_height'];
                }else{
                    $pheight ="500px";
                }
            }
            $pos = strpos($pwidth, '%');
            if($pos === false){
                $pos1 = strpos($pwidth, 'px');
                if($pos1 === false){
                    $pwidth=$pwidth.'px';
                }
            }
            $pos = strpos($pheight, '%');
            if($pos === false){
                $pos1 = strpos($pheight, 'px');
                if($pos1 === false){
                    $pheight=$pheight.'px';
                }
            }
            if($url!='')
            {
                echo '<a data-fancybox data-type="iframe"  class="fancybox" href="'.$url.'"><img src="'.$icon.'" alt="3D model"/></a>';
                echo '<style>.fancybox-slide--iframe .fancybox-content {width: 100%;height: 100%;    max-width:'.$pwidth.';max-height:'.$pheight.'; }</style>';
            }
        }
    }
    
    
    
}
