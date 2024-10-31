<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://parstools.com/?p=14751
 * @since      1.0.0
 *
 * @package    Parstools_Social_Sharing
 * @subpackage Parstools_Social_Sharing/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Parstools_Social_Sharing
 * @subpackage Parstools_Social_Sharing/admin
 * @author     Parstools <parsgateco@gmail.com>
 */
class Parstools_Social_Sharing_Admin
{
	private $options = array();
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
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $options)
	{
		$this->options = $options;
		$this->plugin_name = $plugin_name;
		$this->version = $version;
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Parstools_Social_Sharing_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Parstools_Social_Sharing_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/parstools-social-sharing-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Parstools_Social_Sharing_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Parstools_Social_Sharing_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/parstools-social-sharing-admin.js', array( 'jquery' ), $this->version, false );

	}
	
	public function admin_init()
	{
		register_setting('parstools_social_sharing_options', $this->plugin_name, array($this, 'validate'));
	}
	public function admin_notices()
	{
		if(!current_user_can('manage_options'))
			return;
		
		global $pagenow;
		if(!in_array($pagenow,array("index.php","plugins.php","options-general.php")))
			return;
		?>
        <div class="pst_notice notice notice-success is-dismissible updated">
        <p><?php _e('Thank you for using <a target="_blank" href="http://parstools.com/?p=14751">Parstools social sharing</a>.<br />Please go to <a href="options-general.php?page=parstools_social_sharing_options">settings</a> page and choose theme and position.', 'parstools-social-sharing');?>
        </p>
        </div>
		<?php
	}
	public function admin_footer()
	{
		?>
		<script type="text/javascript" >
        jQuery(document).ready(function($) {
			jQuery(document).on( 'click', '.pst_notice .notice-dismiss', function() {
				var data = {
                	'action': 'dismiss_notices'
				};
				jQuery.post(ajaxurl, data);
			});
        });
        </script>
		<?php
    }
	public function wp_ajax_dismiss_notices()
	{
		$options = $this->options;
		$options["first_install_notic"] = true;
		update_option($this->plugin_name,$options);
		wp_die(); // this is required to terminate immediately and return a proper response
	}
	public function validate($input)
	{
		if(isset($input['general']['theme']))
			$input['general']['theme'] = sanitize_text_field($input['general']['theme']);
		else
			$input['general']['theme'] = 1;
		
		$input['general']['text'] = sanitize_text_field($input['general']['text']);
		
		if(isset($input['general']['position']))
			$input['general']['position'] = sanitize_text_field($input['general']['position']);
		else
			$input['general']['position'] = "before_content";
		
		$input['general']['align'] = (isset($input['general']['align']) ? $input['general']['align'] : 'left');
		
		$input['general']['in_archive'] = (isset($input['general']['in_archive']) && $input['general']['in_archive'] == 1 ? true : false);
		$input['general']['in_front_page'] = (isset($input['general']['in_front_page']) && $input['general']['in_front_page'] == 1 ? true : false);
		$input['general']['in_single'] = (isset($input['general']['in_single']) && $input['general']['in_single'] == 1 ? true : false);
		
		$input['general']['type'] = (isset($input['general']['type']) && in_array($input['general']['type'],array("icon","single")) ? $input['general']['type'] : 'icon');
		
		if(!isset($input['general']['size']))
		{
			$input['general']['size'] = "medium";
		}
		
		if(isset($input['general']['posttypes']))
		{
			if(!is_null($input['general']['posttypes']) && is_array($input['general']['posttypes']))
				$input['general']['posttypes'] = implode(",",$input['general']['posttypes']);
			
		}
		else
		{
			$input['general']['posttypes'] = "post";
		}
		
		if(isset($input['general']['services']))
		{
			if((!is_null($input['general']['services'])) && is_array($input['general']['services']))
				$input['general']['services'] = implode(",",$input['general']['services']);
			
		}
		else
		{
			$input['general']['services'] = "facebook,googleplus";
		}
		
		/******************************************************************************/
		
		if(isset($input['float']['theme']))
			$input['float']['theme'] = sanitize_text_field($input['float']['theme']);
		else
			$input['float']['theme'] = 1;
		
		
		$input['float']['fixed'] = (isset($input['float']['fixed']) && $input['float']['fixed'] == 1 ? true : false);
		$input['float']['in_archive'] = (isset($input['float']['in_archive']) && $input['float']['in_archive'] == 1 ? true : false);
		$input['float']['in_front_page'] = (isset($input['float']['in_front_page']) && $input['float']['in_front_page'] == 1 ? true : false);
		$input['float']['in_single'] = (isset($input['float']['in_single']) && $input['float']['in_single'] == 1 ? true : false);
		
		$input['float']['type'] = (isset($input['float']['type']) && in_array($input['float']['type'],array("fixedR","fixedL")) ? $input['float']['type'] : 'fixedR');
		
		if(!isset($input['float']['size']))
		{
			$input['float']['size'] = "medium";
		}
		
		if(isset($input['float']['posttypes']))
		{
			if(!is_null($input['float']['posttypes']) && is_array($input['float']['posttypes']))
				$input['float']['posttypes'] = implode(",",$input['float']['posttypes']);
			
		}
		else
		{
			$input['float']['posttypes'] = "post";
		}
		
		if(isset($input['float']['services']))
		{
			if((!is_null($input['float']['services'])) && is_array($input['float']['services']))
				$input['float']['services'] = implode(",",$input['float']['services']);
			
		}
		else
		{
			$input['float']['services'] = "facebook,googleplus";
		}
		
		
		if(isset($input['dismiss_first_install_notic']) && $input['dismiss_first_install_notic'] == 1)
			$input['first_install_notic'] = true;
		
		return $input;
	}
	public function plugin_page_settings_link($actions, $file)
	{
		if(false !== strpos($file, 'parstools-social-sharing'))
			$actions['settings'] = '<a href="options-general.php?page=parstools_social_sharing_options">'.__("Settings","parstools-social-sharing").'</a>';
		return $actions; 
	}
	public function add_setting_page()
	{
		add_options_page(__("Parstools Social Sharing","parstools-social-sharing"), __("Parstools Social Sharing","parstools-social-sharing"), 'manage_options', 'parstools_social_sharing_options', array($this, 'setting_page'));
	}
	private function setting_tabs($current = 'settings')
	{
		$tabs = array(
			'settings' => __("Settings","parstools-social-sharing"),
			'float' => __("Float share bar","parstools-social-sharing"),
			'other' => __("Other Plugins","parstools-social-sharing")
		);
		
		echo '<h2 class="nav-tab-wrapper">';
		foreach( $tabs as $tab => $name )
		{
			$class = ( $tab == $current ) ? ' nav-tab-active' : '';
			echo "<a class='nav-tab$class' href='options-general.php?page=parstools_social_sharing_options&tab=$tab'>$name</a>";
	
		}
		echo '</h2>';
	}
	public function setting_page()
	{
		$options = $this->options;
		
		$services = array(
			"facebook" => __("Facebook","parstools-social-sharing"),
			"googleplus" => __("Google Plus","parstools-social-sharing"),
			"parstools" => __("Parstools","parstools-social-sharing"),
			"telegram" => __("Telegram","parstools-social-sharing"),
			"twitter" => __("Twitter","parstools-social-sharing"),
			"cloob" => __("Cloob","parstools-social-sharing"),
			"linkedin" => __("Linkedin","parstools-social-sharing"),
			"skype" => __("Skype","parstools-social-sharing"),
			"whatsapp" => __("WhatsApp","parstools-social-sharing"),
			"line" => __("Line","parstools-social-sharing"),
			"sms" => __("SMS","parstools-social-sharing"),
			"viber" => __("Viber","parstools-social-sharing"),
			"print" => __("Print","parstools-social-sharing"),
			"pinterest" => __("Pinterest","parstools-social-sharing")
		);
		
		?>
		<div class="wrap">
			<h2><?php _e("Parstools Social Sharing","parstools-social-sharing"); ?></h2>
            
            <?php
			if (isset($_GET['tab']))
				$this->setting_tabs($_GET['tab']);
			else
				$this->setting_tabs('settings');
			?>
            <form method="post" action="options.php">
				<?php settings_fields('parstools_social_sharing_options'); ?>
                <input type="hidden" name="<?php echo $this->plugin_name?>[dismiss_first_install_notic]" value="1" />
                <div id="sections">
                    <section>
                        <table class="form-table">
                            <tr valign="top"><th scope="row"><?php _e("Theme: ","parstools-social-sharing"); ?></th>
                                <td>
                                <div>
                                <?php
                                $theme_dir = plugin_dir_path( __FILE__ ) . 'themes/';
                                $theme_url = plugin_dir_url( __FILE__ ) . 'themes/';
                                
                                $i = 0;
                                $files = array();
                                foreach (glob($theme_dir."*/facebook.svg") as $file)
                                {
                                    $name = str_ireplace($theme_dir,"",$file);
                                    $name = explode("/",$name);
                                    $name = str_ireplace("facebook.svg","",$name[0]);
                                    
                                    $files[] = $name;
                                }
                                natsort($files);
                                
                                foreach($files as $file)
                                {
                                    if ($file == $options['general']['theme'])
                                        $check="checked";
                                    else
                                        $check="";
                                    
                                    echo '<div class="theme"><input type="radio" id="'.$file.'" name="'.$this->plugin_name.'[general][theme]" value="'.$file.'" '.$check.' /><label for="'.$file.'"><div style="width:48px;height:48px;background-size:48px;display:inline-block;background-image:url('.$theme_url."/".$file."/facebook.svg".')"></div></label></div>';
                                    $i++;
                                }
                                ?>
                                </div>
                                </td>
                            </tr>
                            <tr valign="top"><th scope="row"><?php _e("Size: ","parstools-social-sharing"); ?></th>
                                <td>
                                <input type="hidden" name="<?php echo $this->plugin_name?>[general][size]" value="<?php echo $options['general']['size']; ?>" />
                                <label for="small" class="theme"><input type="radio" id="small" name="general_size" value="small" <?php if($options['general']['size'] == "small") echo 'checked="checked"'; ?> /><img src="<?php echo $theme_url."/24.png" ?>" /></label>
                                
                                <label for="medium" class="theme"><input type="radio" id="medium" name="general_size" value="medium" <?php if($options['general']['size'] == "medium") echo 'checked="checked"'; ?> /><img src="<?php echo $theme_url."/32.png" ?>" /></label>
                                
                                <label for="big" class="theme"><input type="radio" id="big" name="general_size" value="big" <?php if($options['general']['size'] == "big") echo 'checked="checked"'; ?> /><img src="<?php echo $theme_url."/48.png" ?>" /></label>
                                
                                <label for="custom" class="theme"><input type="radio" id="custom" name="general_size" value="custom" <?php if(is_numeric($options['general']['size'])) echo 'checked="checked"'; ?> /><?php _e("Custom","parstools-social-sharing"); ?></label>
                                
                                <div id="custom_size_general_div" style="display:none;">
                                    <label for="custom_size_general" class="theme"><?php _e("Custom Size: ","parstools-social-sharing"); ?><input type="text" id="custom_size_general" name="custom_size_general" value="<?php if(is_numeric($options['general']['size'])) echo $options['general']['size']; else echo 48;?>" style="width:40px;" /><span>px</span></label>
                                </div>
                                </td>
                            </tr>
                            
                            <tr valign="top"><th scope="row"><?php _e("Services: ","parstools-social-sharing"); ?>
                            <br />
                            <div class="checkAll"><label><input type="checkbox" id="checkAll_general" checked="checked" name="checkAll" value="1" /><?php _e("Select All","parstools-social-sharing"); ?></label></div>
                            </th>
                                <td>
                                <?php
                                $selected_services = '';
                                
                                if($options['general']['services'])
                                    $selected_services = @explode(",",$options['general']['services']);
                                
                                foreach($services as $service=>$name)
                                {
                                    ?>
                                    <div class="theme"><label><input type="checkbox" <?php if(is_array($selected_services) && in_array($service,$selected_services)) echo 'checked="checked"'; ?> name="<?php echo $this->plugin_name?>[general][services][<?php echo $service?>]" value="<?php echo $service?>" /><?php echo $name?></label></div>
                                    <?php
                                }
                                
                                ?>
                                
                                </td>
                            </tr>
                            
                            <tr valign="top"><th scope="row"><?php _e("Type: ","parstools-social-sharing"); ?></th>
                                <td>
                                <input type="hidden" name="<?php echo $this->plugin_name?>[general][type]" value="<?php echo $options['general']['type']; ?>" />
                                
                                <label for="icon" class="theme"><input type="radio" id="icon" name="type" value="icon" <?php if($options['general']['type'] == "icon") echo 'checked="checked"'; ?> /><?php _e("Simple(icon)","parstools-social-sharing"); ?></label>
                                
                                <label for="single" class="theme"><input type="radio" id="single" name="type" value="single" <?php if($options['general']['type'] == "single") echo 'checked="checked"'; ?> /><?php _e("Single Share Icon","parstools-social-sharing"); ?></label>
                                
                                <br />
                                <div id="margin_div" class="theme" style="max-height:none;margin-top: 10px;">
                                    <label for="text"><?php _e("Text before icon(s): ","parstools-social-sharing"); ?><input type="text" id="text" name="<?php echo $this->plugin_name?>[general][text]" value="<?php if($options['general']['text']) echo $options['general']['text']; else echo '';?>" maxlength="40" style="width:140px;" /></label>
                                    <br />
                                    <label for="margintop"><?php _e("Top margin: ","parstools-social-sharing"); ?><input type="text" id="margintop" name="<?php echo $this->plugin_name?>[general][margintop]" value="<?php if(is_numeric($options['general']['margintop'])) echo $options['general']['margintop']; else echo 10;?>" style="width:40px;" /><span>px</span></label>
                                    <br />
                                    <label for="marginbottom"><?php _e("Bottom margin: ","parstools-social-sharing"); ?><input type="text" id="marginbottom" name="<?php echo $this->plugin_name?>[general][marginbottom]" value="<?php if(is_numeric($options['general']['marginbottom'])) echo $options['general']['marginbottom']; else echo 10;?>" style="width:40px;" /><span>px</span></label>
                                    <br />
                                </div>
                                </td>
                            </tr>
                            
                            <tr valign="top"><th scope="row"><?php _e("Position: ","parstools-social-sharing"); ?></th>
                                <td>
                                <select class="theme" name="<?php echo $this->plugin_name?>[general][position]">
                                    <option value="before_content" <?php if($options['general']['position'] == "before_content") echo 'selected="selected"'; ?> ><?php _e("Before Content","parstools-social-sharing"); ?></option>
                                    <option value="after_content" <?php if($options['general']['position'] == "after_content") echo 'selected="selected"'; ?> ><?php _e("After Content","parstools-social-sharing"); ?></option>
                                    <option value="manual" <?php if($options['general']['position'] == "manual") echo 'selected="selected"'; ?> ><?php _e("Manual","parstools-social-sharing"); ?></option>
                                </select>
                                
                                <select class="theme" name="<?php echo $this->plugin_name?>[general][align]">
                                    <option value="left" <?php if($options['general']['align'] == "left") echo 'selected="selected"'; ?> ><?php _e("Left","parstools-social-sharing"); ?></option>
                                    <option value="right" <?php if($options['general']['align'] == "right") echo 'selected="selected"'; ?> ><?php _e("Right","parstools-social-sharing"); ?></option>
                                    <option value="center" <?php if($options['general']['align'] == "center") echo 'selected="selected"'; ?> ><?php _e("Center","parstools-social-sharing"); ?></option>
                                </select>
                                <small style="font-size:12px"><?php _e("Select where would you like to display the buttons. Use [pst] shortcode for manual display.","parstools-social-sharing"); ?></small>
                                </td>
                            </tr>
                            <tr valign="top"><th scope="row"><?php _e("Show in: ","parstools-social-sharing"); ?></th>
                                <td>
                                
                                <div class="theme"><label for="in_archive"><?php _e("Archive ","parstools-social-sharing"); ?></label><input type="checkbox" id="in_archive" name="<?php echo $this->plugin_name?>[general][in_archive]" value="1" <?php if($options['general']['in_archive']) echo 'checked="checked"'; ?> /></div>
                                
                                <div class="theme"><label for="in_front_page"><?php _e("Front Page ","parstools-social-sharing"); ?></label><input type="checkbox" id="in_front_page" name="<?php echo $this->plugin_name?>[general][in_front_page]" value="1" <?php if($options['general']['in_front_page']) echo 'checked="checked"'; ?> /></div>
                                
                                <div class="theme"><label for="in_single"><?php _e("Single Post","parstools-social-sharing"); ?></label><input type="checkbox" id="in_single" name="<?php echo $this->plugin_name?>[general][in_single]" value="1" <?php if($options['general']['in_single']) echo 'checked="checked"'; ?> /></div>
                                
                                </td>
                            </tr>
                            <tr valign="top"><th scope="row"><?php _e("Show in post types: ","parstools-social-sharing"); ?></th>
                                <td>
                                <?php
                                $types = get_post_types(array('public' => true));
                                
                                $posttypes = explode(",",$options['general']['posttypes']);
                                $posttypes = array_flip($posttypes);
                                
                                foreach($types as $type)
                                {
                                ?>
                                <div class="theme"><label for="<?php echo $type; ?>"><?php echo $type; ?></label><input type="checkbox" id="<?php echo $type; ?>" name="<?php echo $this->plugin_name?>[general][posttypes][]" value="<?php echo $type; ?>" <?php if(isset($posttypes[$type])) echo 'checked="checked"'; ?> /></div>
                                <?php
                                }
                                
                                ?>
                                </td>
                            </tr>
                        </table>
                    </section>
                    <section>
                        <table class="form-table" id="float">
                        	<tr valign="top"><th scope="row"><?php _e("Float share bar: ","parstools-social-sharing"); ?></th>
                                <td>
                                <label for="fixed" class="theme"><input type="checkbox" id="fixed" name="<?php echo $this->plugin_name?>[float][fixed]" value="1" <?php if($options['float']['fixed']) echo 'checked="checked"'; ?> /><?php _e("Enable","parstools-social-sharing"); ?></label>
                                </td>
                            </tr>
                            <tr valign="top"><th scope="row"><?php _e("Theme: ","parstools-social-sharing"); ?></th>
                                <td>
                                <div>
                                <?php
                                $theme_dir = plugin_dir_path( __FILE__ ) . 'themes/';
                                $theme_url = plugin_dir_url( __FILE__ ) . 'themes/';
                                
                                $i = 0;
                                $files = array();
                                foreach (glob($theme_dir."*/facebook.svg") as $file)
                                {
                                    $name = str_ireplace($theme_dir,"",$file);
                                    $name = explode("/",$name);
                                    $name = str_ireplace("facebook.svg","",$name[0]);
                                    
                                    $files[] = $name;
                                }
                                natsort($files);
                                
                                foreach($files as $file)
                                {
                                    if ($file == $options['float']['theme'])
                                        $check="checked";
                                    else
                                        $check="";
                                    
                                    echo '<div class="theme"><input type="radio" id="float_'.$file.'" name="'.$this->plugin_name.'[float][theme]" value="'.$file.'" '.$check.' /><label for="float_'.$file.'"><div style="width:48px;height:48px;background-size:48px;display:inline-block;background-image:url('.$theme_url."/".$file."/facebook.svg".')"></div></label></div>';
                                    $i++;
                                }
                                ?>
                                </div>
                                </td>
                            </tr>
                            <tr valign="top"><th scope="row"><?php _e("Size: ","parstools-social-sharing"); ?></th>
                                <td>
                                <input type="hidden" name="<?php echo $this->plugin_name?>[float][size]" value="<?php echo $options['float']['size']; ?>" />
                                
                                <label for="float_small" class="theme"><input type="radio" id="float_small" name="float_size" value="small" <?php if($options['float']['size'] == "small") echo 'checked="checked"'; ?> /><img src="<?php echo $theme_url."/24.png" ?>" /></label>
                                
                                <label for="float_medium" class="theme"><input type="radio" id="float_medium" name="float_size" value="medium" <?php if($options['float']['size'] == "medium") echo 'checked="checked"'; ?> /><img src="<?php echo $theme_url."/32.png" ?>" /></label>
                                
                                <label for="float_big" class="theme"><input type="radio" id="float_big" name="float_size" value="big" <?php if($options['float']['size'] == "big") echo 'checked="checked"'; ?> /><img src="<?php echo $theme_url."/48.png" ?>" /></label>
                                
                                <label for="float_custom" class="theme"><input type="radio" id="float_custom" name="float_size" value="custom" <?php if(is_numeric($options['float']['size'])) echo 'checked="checked"'; ?> /><?php _e("Custom","parstools-social-sharing"); ?></label>
                                
                                <div id="custom_size_float_div" style="display:none;">
                                    <label for="custom_size_float" class="theme"><?php _e("Custom Size: ","parstools-social-sharing"); ?><input type="text" id="custom_size_float" name="custom_size_float" value="<?php if(is_numeric($options['float']['size'])) echo $options['float']['size']; else echo 48;?>" style="width:40px;" /><span>px</span></label>
                                </div>
                                </td>
                            </tr>
                            <tr valign="top"><th scope="row"><?php _e("Services: ","parstools-social-sharing"); ?>
                            <br />
                            <div class="checkAll"><label><input type="checkbox" id="checkAll_float" checked="checked" name="checkAll" value="1" /><?php _e("Select All","parstools-social-sharing"); ?></label></div>
                            </th>
                                <td>
                                
                                <?php
                                $selected_services = '';
                                
                                if($options['float']['services'])
                                    $selected_services = @explode(",",$options['float']['services']);
                                
                                foreach($services as $service=>$name)
                                {
                                    ?>
                                    <div class="theme"><label><input type="checkbox" <?php if(is_array($selected_services) && in_array($service,$selected_services)) echo 'checked="checked"'; ?> name="<?php echo $this->plugin_name?>[float][services][<?php echo $service?>]" value="<?php echo $service?>" /><?php echo $name?></label></div>
                                    <?php
                                }
                                
                                ?>
                                
                                </td>
                            </tr>
                            
                            <tr valign="top"><th scope="row"><?php _e("Position: ","parstools-social-sharing"); ?></th>
                                <td>
                                <input type="hidden" name="<?php echo $this->plugin_name?>[float][type]" value="<?php echo $options['float']['type']; ?>" />
                                <label for="fixedL" class="theme"><input type="radio" id="fixedL" name="fixed_type" value="fixedL" <?php if($options['float']['type'] == "fixedL" || ($options['float']['type'] != "fixedL" && $options['float']['type'] != "fixedR")) echo 'checked="checked"'; ?> /><?php _e("Left docked","parstools-social-sharing"); ?></label>
                                
                                <label for="fixedR" class="theme"><input type="radio" id="fixedR" name="fixed_type" value="fixedR" <?php if($options['float']['type'] == "fixedR") echo 'checked="checked"'; ?> /><?php _e("Right docked","parstools-social-sharing"); ?></label>
                                
                                <br />
                                <label for="topoffset" class="theme"><?php _e("Top offset: ","parstools-social-sharing"); ?><input type="text" id="topoffset" name="<?php echo $this->plugin_name?>[float][topoffset]" value="<?php if(is_numeric($options['float']['topoffset'])) echo $options['float']['topoffset']; else echo 30;?>" style="width:40px;" /><span>px</span></label>
                                </td>
                            </tr>
                            
                            <tr valign="top"><th scope="row"><?php _e("Show in: ","parstools-social-sharing"); ?></th>
                                <td>
                                
                                <div class="theme"><label for="float_in_archive"><?php _e("Archive ","parstools-social-sharing"); ?></label><input type="checkbox" id="float_in_archive" name="<?php echo $this->plugin_name?>[float][in_archive]" value="1" <?php if($options['float']['in_archive']) echo 'checked="checked"'; ?> /></div>
                                
                                <div class="theme"><label for="float_in_front_page"><?php _e("Front Page ","parstools-social-sharing"); ?></label><input type="checkbox" id="float_in_front_page" name="<?php echo $this->plugin_name?>[float][in_front_page]" value="1" <?php if($options['float']['in_front_page']) echo 'checked="checked"'; ?> /></div>
                                
                                <div class="theme"><label for="float_in_single"><?php _e("Single Post","parstools-social-sharing"); ?></label><input type="checkbox" id="float_in_single" name="<?php echo $this->plugin_name?>[float][in_single]" value="1" <?php if($options['float']['in_single']) echo 'checked="checked"'; ?> /></div>
                                
                                </td>
                            </tr>
                            
                            <tr valign="top"><th scope="row"><?php _e("Show in post types: ","parstools-social-sharing"); ?></th>
                                <td>
                                <?php
                                $types = get_post_types(array('public' => true));
                                
                                $posttypes = explode(",",$options['float']['posttypes']);
                                $posttypes = array_flip($posttypes);
                                
                                foreach($types as $type)
                                {
                                ?>
                                <div class="theme"><label for="float_<?php echo $type; ?>"><?php echo $type; ?></label><input type="checkbox" id="float_<?php echo $type; ?>" name="<?php echo $this->plugin_name?>[float][posttypes][]" value="<?php echo $type; ?>" <?php if(isset($posttypes[$type])) echo 'checked="checked"'; ?> /></div>
                                <?php
                                }
                                
                                ?>
                                </td>
                            </tr>
                        </table>
                    </section>
                    <section>
                        <table class="form-table" width="100%" height="100%">
                        <?php
						$lang = get_bloginfo("language");
						if($lang)
							$lang = "?lang=".$lang."&pn=".$this->plugin_name."&pv=".$this->version;
						else
							$lang = "?pn=".$this->plugin_name."&pv=".$this->version;
						?>
                        	<tr valign="top"><td><iframe width="100%" height="768" src="http://parstools.com/parstools_wp_plugins/all_plugins.php<?php echo $lang ?>"></iframe></td></tr>
                        </table>
                    </section>
                </div>
            	<p class="submit">
					<input type="submit" class="button-primary" value="<?php _e("Save Settings","parstools-social-sharing"); ?>" />
				</p>
			</form>
            <p><?php _e("More tools and widget in <a href=\"http://parstools.com\">Parstools.com</a>","parstools-social-sharing"); ?></p>
		</div>
        
        <script type="text/javascript">
        jQuery(document).ready(function($) {
			
			jQuery("#checkAll_general").change(function () {
				jQuery("input:checkbox[name^='<?php echo $this->plugin_name?>[general][services]']").prop('checked', jQuery(this).prop("checked"));
			});
			jQuery("#checkAll_float").change(function () {
				jQuery("input:checkbox[name^='<?php echo $this->plugin_name?>[float][services]']").prop('checked', jQuery(this).prop("checked"));
			});
			
			
			jQuery("input:checkbox[name^='<?php echo $this->plugin_name?>[general][services]']").change(function(){
				if (jQuery("input:checkbox[name^='<?php echo $this->plugin_name?>[general][services]']:checked").length == jQuery("input:checkbox[name^='<?php echo $this->plugin_name?>[general][services]']").length)
					jQuery("#checkAll_general").prop('checked', jQuery(this).prop("checked"));
				else
					jQuery("#checkAll_general").prop('checked',false);
			});
			jQuery("input:checkbox[name^='<?php echo $this->plugin_name?>[float][services]']").change(function(){
				if (jQuery("input:checkbox[name^='<?php echo $this->plugin_name?>[float][services]']:checked").length == jQuery("input:checkbox[name^='<?php echo $this->plugin_name?>[float][services]']").length)
					jQuery("#checkAll_float").prop('checked', jQuery(this).prop("checked"));
				else
					jQuery("#checkAll_float").prop('checked',false);
			});
			
			
			jQuery("input:checkbox[name^='<?php echo $this->plugin_name?>[general][services]']").trigger("change");
			jQuery("input:checkbox[name^='<?php echo $this->plugin_name?>[float][services]']").trigger("change");
			
			
			
			jQuery("input[type=radio][name=type]").click(function ()
			{
				jQuery("input[type=hidden][name='<?php echo $this->plugin_name?>[general][type]']").val(jQuery(this).val());
			})
			jQuery("input[type=radio][name=fixed_type]").click(function ()
			{
				jQuery("input[type=hidden][name='<?php echo $this->plugin_name?>[float][type]']").val(jQuery(this).val());
			})
			
			
			jQuery("input[type=radio][name=type]:checked").trigger("click");
			jQuery("input[type=radio][name=fixed_type]:checked").trigger("click");
			
			
			
			/////////////////////////////////////////////////////////////////
			jQuery("#topoffset, #custom_size_general, #custom_size_float, #margintop, #marginbottom").keydown(function (e) {
				// Allow: backspace, delete, tab, escape, enter and .
				if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
					 // Allow: Ctrl+A, Command+A
					(e.keyCode == 65 && ( e.ctrlKey === true || e.metaKey === true ) ) || 
					 // Allow: home, end, left, right, down, up
					(e.keyCode >= 35 && e.keyCode <= 40)) {
						 // let it happen, don't do anything
						 return;
				}
				// Ensure that it is a number and stop the keypress
				if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
					e.preventDefault();
				}
			});
			
			jQuery("input[type=radio][name=general_size]").click(function ()
			{
				if(jQuery(this).val() == "custom")
				{
					jQuery("#custom_size_general_div").show(500);
					jQuery("#custom_size_general").focus();
					jQuery("input[type=hidden][name='<?php echo $this->plugin_name?>[general][size]']").val(jQuery("#custom_size_general").val());
				}
				else
				{
					jQuery("#custom_size_general_div").hide(500);
					jQuery("input[type=hidden][name='<?php echo $this->plugin_name?>[general][size]']").val(jQuery(this).val());
				}
			});
			jQuery("input[type=radio][name=float_size]").click(function ()
			{
				if(jQuery(this).val() == "custom")
				{
					jQuery("#custom_size_float_div").show(500);
					jQuery("#custom_size_float").focus();
					jQuery("input[type=hidden][name='<?php echo $this->plugin_name?>[float][size]']").val(jQuery("#custom_size_float").val());
				}
				else
				{
					jQuery("#custom_size_float_div").hide(500);
					jQuery("input[type=hidden][name='<?php echo $this->plugin_name?>[float][size]']").val(jQuery(this).val());
				}
			});
			
			
			jQuery("#custom_size_general").change(function(){
				jQuery("input[type=hidden][name='<?php echo $this->plugin_name?>[general][size]']").val(jQuery(this).val());
			});
			jQuery("#custom_size_float").change(function(){
				jQuery("input[type=hidden][name='<?php echo $this->plugin_name?>[float][size]']").val(jQuery(this).val());
			});
			
			
			jQuery("input[type=radio][name=general_size]:checked").trigger("click");
			jQuery("input[type=radio][name=float_size]:checked").trigger("click");
			
			/*
			jQuery("#fixed").change(function () {
				jQuery("#float :input").attr("disabled", !jQuery(this).prop("checked"));
				jQuery(this).attr("disabled", false);
			});
			
			jQuery("#fixed").trigger("change");
			*/
			
			$(document).on( 'click', '.nav-tab-wrapper a', function() {
				
				$(".nav-tab-wrapper a").removeClass('nav-tab-active');
				$(this).addClass('nav-tab-active');
				
				
				$('section').hide();
				$('section').eq($(this).index()).show();
				
				return false;
			})
        });
        </script>
        <?php
	}
}