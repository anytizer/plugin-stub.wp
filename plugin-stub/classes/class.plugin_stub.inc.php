<?php
class plugin_stub
{
	private $whoami = null;

	public function __construct()
	{
	}
	
	public function init($whoami='plugin-stub/plugin-stub.php')
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
		register_activation_hook($whoami, array($this, 'activate'));
		register_deactivation_hook($whoami, array($this, 'deactivate'));
		register_uninstall_hook($whoami, array(__CLASS__, 'uninstall'));
		#add_action( 'activated_plugin',  array($this, 'activate'));
		
		add_shortcode('pluginstub', array($this, '_handle_shortcode'));
		
		add_action('wp_enqueue_scripts', array($this, '_register_scripts'));
		add_action('admin_print_footer_scripts', array($this, '_add_quicktags'));
		add_action('init', array($this, '_js_buttons_init'));
		
		#add_action('admin_notices', array($this, '_notice_success'));
	}
	
	public function activate()
	{
		update_option('_plugin-stub-activated-on', date('Y-m-d H:i:s'));
	}
	
	public function deactivate()
	{
		update_option('_plugin-stub-deactivated-on', date('Y-m-d H:i:s'));
	}
	
	public static function uninstall()
	{
		update_option('_plgin-stub-uninstalled-on', date('Y-m-d H:i:s'));
	}

	# https://codex.wordpress.org/Plugin_API/Action_Reference/admin_notices
	function _notice_success() {
		$class = 'notice notice-success';
		$message = 'Plugin Stub: Great! Plugin works. Checkout all features.';
		printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message); 

		$class = 'notice notice-error is-dismissible';
		$message = 'Plugin Stub: An error has occurred. But do not worry.';
		printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message);
		
		$class = 'notice notice-warning is-dismissible';
		$message = 'Plugin Stub: Warning: Do not worry.';
		printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message);
		
		$class = 'notice notice-info is-dismissible';
		$message = 'Plugin Stub: Info was found.';
		printf('<div class="%1$s"><p>%2$s</p></div>', $class, $message);
	}
	
	/**
	 * @param array $attributes
	 *
	 * @return string
	 */
	public function _handle_shortcode($attributes = array())
	{
		$attributes = array_map('esc_attr', $attributes);
		$standard_attributes = array(
			# Add parameters
		);
		$attributes = shortcode_atts($standard_attributes, $attributes);
		
		# Do the HTML
		$html = "Processed with Shortcode [pluginstub].";
		return $html;
	}

	/**
	 * Main page to display contents
	 */
	public function _help_page()
	{
		require_once(__PLUGIN_STUB_ROOT__.'/pages/help-page.php');
	}

	public function _admin_menu()
	{
		# https://developer.wordpress.org/resource/dashicons/#carrot
		$icon = 'dashicons-carrot';
		add_menu_page('Plugin Stub', 'Plugin Stub', 'manage_options', $this->whoami, array($this, '_help_page'), $icon, 80 );
		wp_enqueue_style('plugin-stub', plugins_url( 'pages/css/style.css', dirname(__FILE__)));
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
				'feedback' => "<a href='http://www.example.com/plugin-stub/feedback/'>Feedback</a>",
				#'file' => $file,
			);
			
			$links = array_merge( $links, $new_links );
		}
		
		return $links;
	}
	
	public function _render_meta_box()
	{
		require_once(__PLUGIN_STUB_ROOT__.'/pages/meta-box.php');
	}

	public function _meta_boxes_post($post) {
		add_meta_box( 
			'my-meta-box',
			'Plugin Stub - Meta Box',
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
		wp_register_style('plugin-stub', plugins_url('css/pages/style.css', __PLUGIN_STUB_ROOT__));
		wp_enqueue_style('plugin-stub');
	}
	
	public function _add_quicktags()
	{
		if (wp_script_is('quicktags')){
			$quicktags_js = file_get_contents(__PLUGIN_STUB_ROOT__.'/pages/js/quicktags.js');
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
		array_push($buttons, 'separator', 'pluginstub');
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
		$plugin_array['pluginstub'] = plugins_url('pages/js/plugin-stub.js' , __PLUGIN_STUB_ROOT__.'/'.basename(__PLUGIN_STUB_ROOT__));
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
