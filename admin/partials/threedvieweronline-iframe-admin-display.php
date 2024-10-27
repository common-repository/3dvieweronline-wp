<?php
/**
 * 3D Viewer Online WordPress Plugin 
 * Provide a admin area view for the plugin
 * This file is used to markup the admin-facing aspects of the plugin.
 * 
 * @since      1.0.0
 * @package    3DViewerOnline_WP_iframe
 * @subpackage 3DViewerOnline_WP_iframe/admin/partials
 */
?>

<div class="wrap cyno-wrap">
    <?php 
        echo $this->show_messages();
    ?>
    <form method="post" name="threed-settings" id="form-wscc-settings" action="" enctype="multipart/form-data">
        <div class="main">
            
            <h1 class="heading-inline"><?php esc_html_e('3D Viewer Online WordPress Plugin', 'THREED'); ?></h1>
            
            <input id="tab1" type="radio" name="tabs" class="cynotabs hide" checked>
            <label for="tab1" class="cynotabs-label" title="<?php esc_html_e('General Settings', 'THREED'); ?>"><i class="fa fa-cogs" aria-hidden="true"></i> <?php esc_html_e('General Settings', 'THREED'); ?></label>
            
            <input id="tab2" type="radio" name="tabs" class="cynotabs hide">
            <label for="tab2" class="cynotabs-label" title="<?php esc_html_e('Help Links', 'THREED'); ?>"><i class="fa fa-info" aria-hidden="true"></i> <?php esc_html_e('Help Links', 'THREED'); ?></label>

            <!--General Settings-->
            <section class="cynotabs-section" id="content1">
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><label for="threed_enable_product_tab"><?php esc_html_e('Enable Product Tab', 'THREED'); ?>:</label></th>
                            <td>
                                <div class="onoffswitch">
                                    <input type="hidden" value="off" name="threed_enable_product_tab">
                                    <input type="checkbox" name="threed_enable_product_tab" class="onoffswitch-checkbox" id="threed_enable_product_tab" <?php ($this->getSettingValue('threed_enable_product_tab')=='on') ?  esc_attr_e("checked",'THREED'): '';  ?>>
                                    <label class="onoffswitch-label" for="threed_enable_product_tab">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                     
                                </div>
                                <p class="description" id="tagline-description"><?php esc_html_e('Display 3D model in product tab', 'THREED'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="threed_tab_title"><?php esc_html_e('Product Tab Title', 'THREED'); ?>:</label></th>
                            <td>
                                <input class="wscc-input required" name="threed_tab_title" type="text" id="threed_tab_title" value="<?php echo esc_attr($this->getSettingValue('threed_tab_title')); ?>" class="regular-text">
                                <p id="errmsg" style="display:none;"></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="threed_enable_product_icon"><?php esc_html_e('Enable Product 3D Icon', 'THREED'); ?>:</label></th>
                            <td>
                                <div class="onoffswitch">
                                    <input type="hidden" value="off" name="threed_enable_product_icon">
                                    <input type="checkbox" name="threed_enable_product_icon" class="onoffswitch-checkbox" id="threed_enable_product_icon" <?php ($this->getSettingValue('threed_enable_product_icon')=='on') ?  esc_attr_e("checked",'THREED'): '';  ?>>
                                    <label class="onoffswitch-label" for="threed_enable_product_icon">
                                        <span class="onoffswitch-inner"></span>
                                        <span class="onoffswitch-switch"></span>
                                    </label>
                                     
                                </div>
                                <p class="description" id="tagline-description"><?php esc_html_e('Display 3D model icon in product page', 'THREED'); ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="threed_icon_display"><?php esc_html_e('3D Icon Display', 'THREED'); ?>:</label></th>
                            <td>
                                <select class="wscc-select required" name="threed_icon_display" id="threed_icon_display">
                                    <?php 
                                        $displayOn=$this->getDisplayIframeOptions();
                                        if(!empty($displayOn))
                                        {
                                            foreach($displayOn as $id=>$code)
                                            {
                                                $selected='';
                                                
                                                if(isset($this->settings['threed_icon_display']) && !empty($this->settings['threed_icon_display']) && $id== $this->settings['threed_icon_display'])
                                                {
                                                    $selected='selected="selected"';
                                                }

                                                echo '<option value="'.esc_attr($id).'" '.esc_attr($selected).'>'.esc_html($code).'</option>';
                                            }
                                        }
                                    ?>
                                    
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="threed_icon"><?php esc_html_e('3D Icon', 'THREED'); ?>:</label></th>
                            <td>
                                <input class="wscc-input" name="threed_icon" type="text" id="threed_icon" value="<?php echo esc_url($this->getSettingValue('threed_icon'));  ?>" class="regular-text">
                                <input type="button" name="upload-btn" id="upload-btn-1" class="button-secondary wscc-button upload-btn" value="<?php esc_html_e('Upload Icon', 'THREED'); ?>" data-input="threed_icon">
                                <p class="description" id="tagline-description"><?php esc_html_e('Enter a URL or upload an 3D Icon. if you don\'t select then display default', 'THREED'); ?></p>
                            </td>
                        </tr>
                        <?php /*
                        <tr>
                            <th scope="row"><label for="threed_iframe_width"><?php esc_html_e('Iframe Width', 'THREED'); ?>:</label></th>
                            <td>
                                <input class="wscc-input required" name="threed_iframe_width" type="text" id="threed_iframe_width" value="<?php echo esc_attr($this->getSettingValue('threed_iframe_width')); ?>" class="regular-text">
                                <p id="errmsg" style="display:none;"></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="threed_iframe_height"><?php esc_html_e('Iframe Height', 'THREED'); ?>:</label></th>
                            <td>
                                <input class="wscc-input required" name="threed_iframe_height" type="text" id="threed_iframe_height" value="<?php echo esc_attr($this->getSettingValue('threed_iframe_height')); ?>" class="regular-text">
                                <p id="errmsg" style="display:none;"></p>
                            </td>
                        </tr>*/?>
                        
                    </tbody>
                </table>
            </section>
            <section class="cynotabs-section" id="content2">
                <a href="https://www.3dvieweronline.com/wordpress/wp-login.php" class="button button-primary" target="_blank"><?php esc_html_e('Login to 3DVieweronline', 'THREED'); ?></a>
                <a href="https://www.3dvieweronline.com/documentation_cat/embed-the-viewer/" class="button button-primary" target="_blank"><?php esc_html_e('Help', 'THREED'); ?></a>
                <a href="https://www.3dvieweronline.com/" class="button button-primary" target="_blank"><?php esc_html_e('Contact us', 'THREED'); ?></a>
            </section>
            
        </div>
        <p class="submit"><input type="submit" name="threed-save" id="submit" class="button button-primary" value="<?php esc_html_e('Save Changes', 'THREED'); ?>"></p>
        
    </form>
</div>

