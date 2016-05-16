<?php
class plugin_name
{
	private $whoami = null;

	public function __construct()
	{
	}
	
	public function init($whoami='plugin-name/plugin-name.php')
	{
		$this->whoami=$whoami;

		/**
		 * Puts a menu in Admin Panel
		 */
		add_action('admin_menu', array($this, '_admin_menu'));

		/**
		 * Adds a link within list of plugins
		 */
		add_filter("plugin_action_links_{$this->whoami}", array($this, '_action_links'));

		/**
		 * Adds links in plugins row
		 */
		add_action('plugin_row_meta', array($this, '_plugin_row_meta'), 10, 2);

		/**
		 * Adds meta box in post editor
		 */
		add_action('add_meta_boxes_post', array($this, '_meta_boxes_post'));
		
		/**
		 * Install/Uninstall Hooks
		 */
		add_shortcode('plugin-name', array($this, '_handle_shortcode'));
		add_action('wp_enqueue_scripts', array($this, '_register_scripts'));
		add_action('admin_print_footer_scripts', array($this, '_add_quicktags'));
		add_action('init', array($this, '_js_buttons_init'));
	}

	/**
	 * Main page to display contents
	 */
	public function _help_page()
	{
		require_once(__PLUGIN_NAME_ROOT__.'/pages/help-page.php');
	}

	public function _admin_menu(){
		$icon = 'dashicons-info';
		add_menu_page('Plugin Name', 'Plugin Name', 'manage_options', $this->whoami, array($this, '_help_page'), $icon, 80 );
		wp_enqueue_style('plugin-name', plugins_url( 'pages/css/style.css', dirname(__FILE__)));
	}

	public function _action_links($links) {
		$actions = array(
			"<a href='?page={$this->whoami}'>Settings</a>",			
		);
		$links = array_merge($actions, $links);
		return $links;
	}

	public function _plugin_row_meta($links, $file) {

		if($file == $this->whoami)
		{
			$new_links = array(
				'documentation' => '<a href="http://www.example.com/documenation/" target="_blank">Documentation</a>',
				'donatations' => '<a href="http://www.example.com/donate/" target="_blank">Donate</a>',
				'reviews' => '<a href="http://www.example.com/reviews" target="_blank">Reviews</a>',
				'feedback' => "<a href='http://www.example.com/plugin-name/feedback/'>Feedback</a>",
				#'file' => $file,
			);
			
			$links = array_merge( $links, $new_links );
		}
		
		return $links;
	}
	
	public function _render_meta_box()
	{
		#echo "Plugin Name - Meta Box is active.";
		require_once(__PLUGIN_NAME_ROOT__.'/pages/meta-box.php');
	}

	public function _meta_boxes_post($post) {
		add_meta_box( 
			'my-meta-box',
			'Plugin Name - Meta Box',
			array($this, '_render_meta_box'),
			'post',
			'normal',
			'default'
		);
	}
	
	/**
	 * CSS for front end
	 */
	public function _register_scripts()
	{
		wp_register_style('plugin-name', plugins_url('css/pages/style.css', __PLUGIN_NAME_ROOT__));
		wp_enqueue_style('plugin-name');
	}
	
	public function _add_quicktags()
	{
		if (wp_script_is('quicktags')){
			$quicktags_js = file_get_contents(__PLUGIN_NAME_ROOT__.'/pages/js/quicktags.js');
			echo "<script type='text/javascript'>{$quicktags_js}</script>";
		}
	}
	
	/**
	 * Add custom buttons in TinyMCE.
	 *
	 * @param $buttons
	 *
	 * @return mixed
	 */
	function _register_js_buttons( $buttons ) {
		array_push($buttons, 'separator', 'pluginname');
		return $buttons;
	}

	/**
	 * Register button scripts.
	 *
	 * @param $plugin_array
	 *
	 * @return mixed
	 */
	function _add_external_plugins( $plugin_array ) {
		$plugin_array['pluginname'] = plugins_url('pages/js/plugin-name.js' , __PLUGIN_NAME_ROOT__.'/'.basename(__PLUGIN_NAME_ROOT__));
		return $plugin_array;
	}

	/**
	 * Register buttons in init.
	 */
	function _js_buttons_init() {
		if (!current_user_can('edit_posts') && !current_user_can('edit_pages'))
		{
			return;
		}

		if (true == get_user_option('rich_editing'))
		{
			add_filter('mce_buttons', array($this, '_register_js_buttons'));
			add_filter('mce_external_plugins', array($this, '_add_external_plugins'));
		}
	}
}
