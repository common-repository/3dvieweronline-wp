<?php
/**
 * 3D Viewer Online WordPress Plugin
 * The admin-specific functionality of the plugin.
 * 
 * @since      1.0.0
 * @package    3DViewerOnline_WP_iframe
 * @subpackage 3DViewerOnline_WP_iframe/admin
 */

class Threedvieweronline_Iframe_Admin
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
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      Scratch_Coupon_Card_Loader    $loader    Maintains and registers all hooks for the plugin.
     */
     
    private $loader;
    
    /**
     * The settings of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      array    $settings The settings of this plugin.
     */
    private $settings = array();

    /**
     * The Error messages of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      array    $errors    The errors messages of this plugin.
     */
    private $errors = array();

    /**
     * The update messages of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      array    $messages    The update messages of this plugin.
     */
    private $messages = array();

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * 
     */
    
    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( $plugin_name, $version,$loader ) 
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
        $this->loader = $loader;
        
        $this->settings=$this->getSettings();
        
    }
    
    /**
     * Register the JavaScript & CSS for the admin area.
     *
     * @since    1.0.0
     */
    public function admin_enqueue_scripts() 
    {
        wp_enqueue_script($this->plugin_name.'-common-js', plugins_url('/admin/js/threedvieweronline-common-admin.js', THREED_PLUGIN_FILE), array( 'jquery' ), $this->version, false);
        wp_enqueue_style($this->plugin_name.'-common-style', plugins_url('/admin/css/threedvieweronline-common-admin.css', THREED_PLUGIN_FILE), array(), $this->version, 'all');
    }
    
    public function enqueue_styles() 
    {

        wp_enqueue_style($this->plugin_name.'-font-awesome', plugins_url('/admin/css/font-awesome/css/font-awesome.min.css', THREED_PLUGIN_FILE), array(), $this->version, 'all');
        
        wp_enqueue_style($this->plugin_name, plugins_url('/admin/css/threedvieweronline-iframe-admin.css', THREED_PLUGIN_FILE), array(), $this->version, 'all');
        
    }

    public function enqueue_scripts() 
    {

        wp_enqueue_media();
        
        wp_enqueue_script($this->plugin_name, plugins_url('/admin/js/threedvieweronline-iframe-admin.js', THREED_PLUGIN_FILE), array( 'jquery' ), $this->version, false);

    }
    
    public function enqueue_block_assets(){
		
        wp_enqueue_style($this->plugin_name.'-blocks-style', plugins_url('/dist/blocks.style.build.css', THREED_PLUGIN_FILE), array('wp-editor'), $this->version, 'all');
        
	}

	public function enqueue_block_editor_assets(){

		wp_enqueue_script($this->plugin_name.'-blocks', plugins_url('/dist/blocks.build.js', THREED_PLUGIN_FILE), array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), $this->version, false);
        
        wp_enqueue_style($this->plugin_name.'-editor', plugins_url('/dist/blocks.editor.build.css', THREED_PLUGIN_FILE), array('wp-edit-blocks'), $this->version, 'all');
	}
    
    /**
     * Register the tinymce button
     *
     * @since    1.0.0
     */
    public function threed_add_my_custom_tinymce_button() 
    {
        global $typenow;
    
        if( ! in_array( $typenow, array( 'product' ) ) )
            return;
        
        if ( get_user_option('rich_editing') == 'true') {
            add_filter("mce_external_plugins",array( $this, "threed_mce_external_plugins_callack"));
            add_filter('mce_buttons',array( $this, 'threed_mce_buttons_callack'));
        }
    } 
    
    public function threed_mce_external_plugins_callack($plugin_array) 
    {
        $plugin_array['threed_mce_button1'] = plugins_url('/admin/js/threed-mce-button1.js', THREED_PLUGIN_FILE);
        //$plugin_array['threed_mce_button2'] = plugins_url('/admin/js/threed-mce-button2.js', THREED_PLUGIN_FILE);
        
        return $plugin_array;
    } 
    
    public function threed_mce_buttons_callack($buttons) 
    {
        array_push($buttons, "threed_mce_button1");
        //array_push($buttons, "threed_mce_button2");
        
        return $buttons;
    } 
    
    /**
     * Register the product meta boxes
     *
     * @since    1.0.0
     */
    public function threed_product_register_meta_boxes($post_type) 
    {
        if($post_type=='product'){
            add_meta_box( 'threed-viewer-online', __( '3D Viewer Online', 'THREED' ), array($this,'threed_product_meta_box_callback'), 'product' );
        }
    } 
    
    public function threed_product_meta_box_callback($post) 
    {
        if($post->post_type=='product'){
            
            // Add an nonce field so we can check for it later.
            wp_nonce_field( 'threed_viewer_online','threed_viewer_online_nonce');
     
            // Use get_post_meta to retrieve an existing value from the database.
            $value = get_post_meta( $post->ID, 'threed_model_url',true);
            
            $piwidth = get_post_meta( $post->ID, 'threed_piframe_width',true);
            $piheight = get_post_meta( $post->ID, 'threed_piframe_height',true);
            
            // Display the form, using the current value.
            ?>
            <label for="threed_model_url" style="display: block;margin: 10px 0;">
                <strong><?php _e( '3D model url:', 'THREED' ); ?></strong>
            </label>
            <input type="text" 
                id="threed_model_url" 
                name="threed_model_url" 
                value="<?php echo esc_attr( $value ); ?>" 
                style="width: 80%;" />
            <input id="previewBtn" type="button" class="button tagadd" value="Preview">
            
            <label for="threed_piframe_width" style="display: block;margin: 10px 0;">
                <strong><?php _e( 'Iframe Width:', 'THREED' ); ?></strong>
            </label>
            <input type="text" 
                id="threed_piframe_width" 
                name="threed_piframe_width" 
                value="<?php echo esc_attr( $piwidth ); ?>" 
                style="width: 50%;" />
            
            <label for="threed_piframe_height" style="display: block;margin: 10px 0;">
                <strong><?php _e( 'Iframe Height:', 'THREED' ); ?></strong>
            </label>
            <input type="text" 
                id="threed_piframe_height" 
                name="threed_piframe_height" 
                value="<?php echo esc_attr( $piheight ); ?>" 
                style="width: 50%;" />
                
            <div id="threed-iframe-preview" style="padding: 10px;">
                <?php  
                    if($piwidth==''){
                        if(isset($this->settings['threed_iframe_width']) && $this->settings['threed_iframe_width']!='') {
                            $piwidth = $this->settings['threed_iframe_width'];
                        }else{
                            $piwidth ="100%";
                        }
                    }
                    
                    if($piheight==''){
                        if(isset($this->settings['threed_iframe_height']) && $this->settings['threed_iframe_height']!='') {
                            $piheight = $this->settings['threed_iframe_height'];
                        }else{
                            $piheight ="500px";
                        }
                    }
                    
                    $pos = strpos($piwidth, '%');
                    if($pos === false){
                        $pos1 = strpos($piwidth, 'px');
                        if($pos1 === false){
                            $piwidth=$piwidth.'px';
                        }
                    }
                    $pos = strpos($piheight, '%');
                    if($pos === false){
                        $pos1 = strpos($piheight, 'px');
                        if($pos1 === false){
                            $piheight=$piheight.'px';
                        }
                    }
                    
                    if($value!=''){
                        echo '<iframe class="threed_model_iframe" width="100%" height="'.$piheight.'" src="'.$value.'" frameborder="0" allowfullscreen="" style="background-image: none;max-width:'.$piwidth.';" ></iframe>';
                    }
                ?>
            
            </div>
            <?php
            //print_r($post);
        }
    }

    public function threed_product_meta_box_save( $post_id ) {
 
        /*
         * We need to verify this came from the our screen and with proper authorization,
         * because save_post can be triggered at other times.
         */
 
        // Check if our nonce is set.
        if ( ! isset( $_POST['threed_viewer_online_nonce'] ) ) {
            return $post_id;
        }
 
        $nonce = $_POST['threed_viewer_online_nonce'];
 
        // Verify that the nonce is valid.
        if ( ! wp_verify_nonce( $nonce, 'threed_viewer_online' ) ) {
            return $post_id;
        }
 
        /*
         * If this is an autosave, our form has not been submitted,
         * so we don't want to do anything.
         */
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }
 
        /* OK, it's safe for us to save the data now. */
 
        // Sanitize the user input.
        $threed_model_url = sanitize_text_field( $_POST['threed_model_url'] );
        $threed_piframe_width = sanitize_text_field( $_POST['threed_piframe_width'] );
        $threed_piframe_height = sanitize_text_field( $_POST['threed_piframe_height'] );
 
        // Update the meta field.
        update_post_meta( $post_id, 'threed_model_url', $threed_model_url );
        update_post_meta( $post_id, 'threed_piframe_width', $threed_piframe_width );
        update_post_meta( $post_id, 'threed_piframe_height', $threed_piframe_height );
    }
 
    
    /**
     * Register the plugin settings link.
     *
     * @since    1.0.0
     */
    public function plugin_settings_link($links) 
    {

        $action_links = array(
            'settings' => '<a href="' . admin_url('admin.php?page=threed-setting') . '" aria-label="' . esc_attr__('settings', 'THREED') . '">' . esc_html__('Settings', 'THREED') . '</a>',
        );
        return array_merge($action_links, $links);
    } 
    
    /**
	 * Show row meta on the plugin screen.
	 *
	 * @param mixed $links Plugin Row Meta.
	 * @param mixed $file  Plugin Base file.
	 *
	 * @return array
	 */
	public static function plugin_row_meta( $links, $file ) {
		if ( THREED_PLUGIN_BASENAME === $file ) {
			$row_meta = array(
				'youraccount'    => '<a href="' . esc_url( 'https://www.3dvieweronline.com/wordpress/wp-login.php'  ) . '" aria-label="' . esc_attr__( 'Your account', 'THREED' ) . '" target="_blank">' . esc_html__( 'Your account', 'THREED' ) . '</a>',
                'howtousetheplugin'    => '<a href="' . esc_url( 'https://www.3dvieweronline.com/documentation_cat/embed-the-viewer/'  ) . '" aria-label="' . esc_attr__( 'How to use the plugin', 'THREED' ) . '" target="_blank">' . esc_html__( 'How to use the plugin', 'THREED' ) . '</a>',
                'contactus'    => '<a href="' . esc_url( 'https://www.3dvieweronline.com/'  ) . '" aria-label="' . esc_attr__( 'Contact us', 'THREED' ) . '" target="_blank">' . esc_html__( 'Contact us', 'THREED' ) . '</a>',
				
			);

			return array_merge( $links, $row_meta );
		}

		return (array) $links;
	}
    
    /**
     * Register the plugin settings link.
     *
     * @since    1.0.0
     */
    public function admin_menu() 
    {

        $settings_page = add_menu_page(
            '3D Viewer Online WordPress Plugin',
            '3D Viewer Online',
            'manage_options',
            'threed-setting',
            array($this,'output'),
            plugins_url('assets/images/icon.png', THREED_PLUGIN_FILE),
            '80'
        );
        
        add_action('load-'.$settings_page, array( $this, 'settings_page_init' ));
    }
    /**
     * Retrieve all display on page options.
     *
     * @since    1.0.0
     */
    public function getDisplayIframeOptions() 
    {
        
        return array(
            'after-product-title'=>'After product title',
            'after-product-short-description'=>'After product short description',
            'after-add-to-cart-button'=>'After add to cart button',
            
        );
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
     * Retrieve the setting value.
     *
     * @since     1.0.0
     * @return    array    The settings of the plugin.
     */
    public function getSettingValue($setting) 
    {
        if(isset($this->settings[$setting])){
            return $this->settings[$setting];
        }
        return '';
    }
    
    /**
     * Add a update message for settings.
     *
     * @since    1.0.0
     * @access   public
     * @var string $message update Message.
     */
    public function add_message( $message ) 
    {
        $this->messages[] = $message;
    }
    
    /**
     * Add a error message for settings.
     *
     * @since    1.0.0
     * @access   public
     * @var string $error error Message.
     */
    public function add_error( $error ) 
    {
        $this->errors[] = $error;
    }

    /**
     * Retrieve the messages + errors of the plugin.
     *
     * @since     1.0.0
     * 
     */
    public function show_messages() 
    {
        
        $returndata='';
        if (count($this->errors) > 0 ) {
            foreach ($this->errors as $error ) {
                $returndata.= '<div id="message" class="error inline"><p><strong>' . esc_html__($error, 'THREED') . '</strong></p></div>';
            }
        } elseif (count($this->messages) > 0 ) {
            foreach ($this->messages as $message ) {
                $returndata.= '<div id="message" class="updated inline"><p><strong>' . esc_html__($message, 'THREED') . '</strong></p></div>';
            }
        }
        
        return $returndata;
    }
    
    /**
     * Init method for setting page.
     *
     * @since     1.0.0
     * 
     */
    public function settings_page_init() 
    {
        // Save settings if data has been posted.
        
        if (!empty($_POST['threed-save']) )  { 
            $this->save();
        } 

    }
    
    
    /**
     * Retrieve Settings form.
     * Handles the display of the main settings page in admin.
     *
     *@since     1.0.0
     */
    public function output() 
    {
        
        $this->enqueue_styles();
        $this->enqueue_scripts();
        
        require_once THREED_PLUGIN_DIR . '/admin/partials/threedvieweronline-iframe-admin-display.php';
    }
    
    
    /**
     * Save the settings.
     * Handles the save & update settings in database.
     *
     *@since     1.0.0
     */
    public function save() 
    {
         
        if(!$this->validation($_POST))
        {
            return false;
        }
        
        $settings=array();
        $settings['threed_enable_product_tab']=$_POST['threed_enable_product_tab'];
        
        $settings['threed_enable_product_icon']=$_POST['threed_enable_product_icon'];
        
        $settings['threed_tab_title']=esc_attr($_POST['threed_tab_title']);
        
        $settings['threed_icon_display']=$_POST['threed_icon_display'];
        
        $settings['threed_icon']=esc_url($_POST['threed_icon']);
        
        //$settings['threed_iframe_width']=esc_attr($_POST['threed_iframe_width']);
        //$settings['threed_iframe_height']=esc_attr($_POST['threed_iframe_height']);
        $settings['threed_iframe_width']="100%";
        $settings['threed_iframe_height']="500px";
        
        update_option('settings-threed-iframe', $settings, true);
        
        $this->settings=get_option('settings-threed-iframe');
        
        $this->add_message(esc_html__('Your settings have been saved.', 'THREED'));
        
    }
    
    /**
     * validate request data.
     * Handles the request data.
     *
     *@since     1.0.0
     */
    public function validation($data) 
    {
        $supported_image = array('jpg','jpeg','png');
        
        $error=0;
        
        if(isset($data['threed_enable_product_tab']) && $data['threed_enable_product_tab']=='on')
        {
            if(isset($data['threed_tab_title']) && empty($data['threed_tab_title']))
            {
                $this->add_error(esc_html__('Product Tab Title is required.', 'THREED'));
                $error=1;
            }
        }
        
        if(isset($data['threed_icon']) && !empty($data['threed_icon']))
        {
            $image_ext   = pathinfo($data['threed_icon'], PATHINFO_EXTENSION);
            $image_ext   = str_replace('.', '', $image_ext);
            if (!in_array($image_ext, $supported_image))
            {
                $this->add_error(esc_html__('3D Icon must be a file of type: jpeg, png, jpg.', 'THREED'));
                $error=1;
            }
        }
        
        /* if(isset($data['threed_iframe_width']) && empty($data['threed_iframe_width']))
        {
            $this->add_error(esc_html__('Iframe Width is required.', 'THREED'));
            $error=1;
        }
        if(isset($data['threed_iframe_height']) && empty($data['threed_iframe_height']))
        {
            $this->add_error(esc_html__('Iframe Height is required.', 'THREED'));
            $error=1;
        } */
        
        if($error) {
            return false;
        }

        return true;
        
    }
    

}
