<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://parstools.com/?p=14751
 * @since      1.0.0
 *
 * @package    Parstools_Social_Sharing
 * @subpackage Parstools_Social_Sharing/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Parstools_Social_Sharing
 * @subpackage Parstools_Social_Sharing/public
 * @author     Parstools <parsgateco@gmail.com>
 */
class Parstools_Social_Sharing_Public
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
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version, $options)
	{
		$this->options = $options;
		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_shortcode('pst',array($this, 'shortcode'));
	}
	
	public function shortcode($args)
	{
		$options = $this->options;
		extract($options['general']);
		
		if($position != "manual")
			return;
		
		$defaults = array(
			'id' => get_the_ID()
		);
		
		$args = shortcode_atts($defaults, $args);
		
		$id = (int) $args["id"];
		
		$code = $this->prepare_code('general',$id);
		return $code;
	}
	
	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles()
	{
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

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/parstools-social-sharing-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
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

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/parstools-social-sharing-public.js', array( 'jquery' ), $this->version, false );

	}
	
	private function clean_title($title)
	{
		$title = str_ireplace("'","",$title);
		$title = str_ireplace('"','',$title);
		$title = str_ireplace("<",'',$title);
		$title = str_ireplace(">",'',$title);
		$title = str_ireplace("=",'',$title);
		
		
		$title = str_ireplace("/",'',$title);
		$title = str_ireplace("\\",'',$title);
		return $title;
	}
	
	public function add_script_to_footer()
	{
		$options = $this->options;
		
		if($options['float']["fixed"])
		{
			echo $this->prepare_code('float');
		}
		?>
		<!--Parstools Voting Widget-->
		<script type="text/javascript">
		;!(function(e, d) {
			d.write('<scr'+'ipt type="text/javascript" src="http://code.parstools.com/async/share/share.js" async></scri' + 'pt>');})(window, document);</script>
		<!--Parstools Voting Widget-->
	<?php
	}
	
	public function add_sharing_widget_to_content($content)
	{
		if(is_preview() || is_admin())
			return $content;
		
		$code = $this->prepare_code('general',get_the_ID());
		
		$options = $this->options;
		extract($options['general']);
		
		
		if($position == "before_content")
		{
			$content = $code . $content;
		}
		else if($position == "after_content")
		{
			$content = $content . $code;
		}
		
		return $content;
	}
	private function prepare_code($type = 'general',$post_id = 0)
	{
		$options = $this->options;
		
		if($type == "float")
		{
			extract($options['float']);
			
			if(!$in_archive && is_archive())
				return;
			
			if(!$in_single && is_singular())
				return;
			
			if(!$in_front_page && is_front_page())
				return;
			
			$posttype = get_post_type();
			$posttypes = explode(",",$posttypes);
			
			if(isset($posttypes) && !in_array($posttype,$posttypes))
				return;
			
			if($posttype == 'page' && is_front_page())
				return;
			/*
			$title = "";
			$url = "";
			if($post_id > 0)
			{
				$title = $this->clean_title(get_the_title($post_id));
				$url = esc_url(wp_get_shortlink($post_id));
			}
			*/
			return "<!--Parstools Sharing-->
	<script type=\"text/javascript\">;!(function(e, d) {'use strict';var sharing = {id:'pst-' + ~~(Math.random() * 999999),theme:'$theme',size:'$size',type:'$type',topoffset:'$topoffset',text:'',
	url: '',
	title: '',
	services: '$services'};if (typeof e.pstAttrs != 'object') e.pstAttrs = {};e.pstAttrs[sharing.id] = sharing;d.write('<d'+'iv id=\"' + sharing.id + '\"></d'+'iv>');})(window, document);</script>
	<!--Parstools Sharing-->";
		}
		else
		{
			extract($options['general']);
			
			if(!$in_archive && is_archive())
				return;
			
			if(!$in_single && is_singular())
				return;
			
			if(!$in_front_page && is_front_page())
				return;
			
			//global $post;
			
			$posttype = get_post_type();
			$posttypes = explode(",",$posttypes);
			
			if(isset($posttypes) && !in_array($posttype,$posttypes))
				return;
			
			if($posttype == 'page' && is_front_page())
				return;
			
			if($align == "left")
				$align = "text-align:left;";
			else if($align == "right")
				$align = "text-align:right;";
			else if($align == "center")
				$align = "text-align:center;";
			
			$title = "";
			$url = "";
			if($post_id > 0)
			{
				$title = $this->clean_title(get_the_title($post_id));
				$url = esc_url(wp_get_shortlink($post_id));
			}
			
			if(strlen($text) > 2)
				$text = $text . " ";
			
			
			return "<!--Parstools Sharing-->
	<script type=\"text/javascript\">;!(function(e, d) {'use strict';var sharing = {id:'pst-' + ~~(Math.random() * 999999),theme:'$theme',size:'$size',type:'$type',topoffset:'0',text:'$text',
	url: '".$url."',
	title: '".$title."',
	services: '$services'};if (typeof e.pstAttrs != 'object') e.pstAttrs = {};e.pstAttrs[sharing.id] = sharing;d.write('<d'+'iv id=\"' + sharing.id + '\" style=\"$align margin:".$margintop."px 0 ".$marginbottom."px 0\"></d'+'iv>');})(window, document);</script>
	<!--Parstools Sharing-->";
		}
	}
}