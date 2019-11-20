<?php

class DS_AdminColorBarAdmin
{
	private static $_instance = NULL;

	const SETTINGS_PAGE = 'acb-settings';
	const SETTINGS_GROUP = self::SETTINGS_PAGE; // 'loadcb_options_group';
	const SETTINGS_FIELD = 'acb_settings';

	private function __construct()
	{
		// this method is only called when it's an admin page request and the color bar is being displayed

		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );

		// add admin notice
		add_action( 'admin_bar_menu', array( $this, 'admin_bar_notice' ) );

		// style the admin bar
		add_action( 'admin_head', array( $this, 'admin_bar_notice_css' ) );
		add_action( 'wp_head', array( $this, 'admin_bar_notice_css' ) );

		// remove "howdy" message
		add_filter( 'admin_bar_menu', array( $this, 'remove_howdy' ), 25 );

		// configuration page callbacks
		$this->add_settings_menu();
		add_action( 'admin_init', array( $this, 'settings_api_init' ) );
		add_action( 'load-settings_page_' . self::SETTINGS_PAGE, array( $this, 'contextual_help' ) );
	}

	public static function get_instance()
	{
		if ( NULL === self::$_instance )
			self::$_instance = new self();
		return self::$_instance;
	}

	public function admin_bar_notice()
	{
		$text = $this->_get_option( 'message', __( 'DEVELOPMENT WEBSITE', 'admin-color-bar' ) );

		$admin_notice = array(
			'parent'	=> 'top-secondary',						// puts it on the right side
			'id'		=> 'environment-notice',
			'title'		=> '<span>' . esc_html( $text ) . '</span>',
		);

		global $wp_admin_bar;
		$wp_admin_bar->add_menu( $admin_notice );
	}

	/**
	 * Outputs CSS on admin pages to override the Admin Bar appearance
	 */
	public function admin_bar_notice_css()
	{
		$color = $this->_get_option( 'color', '#0073AA' );
		$textcolor = $this->_get_option( 'textcolor', '#eee' );
?>
<!-- DesktopServer Admin Color Bar Notice -->
<style type='text/css'>
	#wp-admin-bar-environment-notice>div,
	#wpadminbar { background-color: <?php echo $color; ?> !important }
	#wp-admin-bar-environment-notice { display: none }
	@media only screen and (min-width: 1030px) {
	    #wp-admin-bar-environment-notice { display: block }
	    #wp-admin-bar-environment-notice > div > span {
	        /*color: #EEE !important;*/
	        font-size: 130% !important;
			line-height: 2em;
	    }
		#wpadminbar #adminbarsearch:before, #wpadminbar .ab-icon:before, #wpadminbar .ab-item:before {
			color: <?php echo $textcolor; ?> !important;
		}
	}
	#wpadminbar .ab-empty-item,#wpadminbar a.ab-item,#wpadminbar>#wp-toolbar span.ab-label,#wpadminbar>#wp-toolbar span.noticon{color:<?php echo $textcolor; ?>;}
	#adminbarsearch:before,
	.ab-icon:before,
	.ab-item:before { color: #EEE !important }
</style>
<?php
	}

	/**
	 * Callback for the 'admin_bar_menu' action. Use this chance to change the contents of the My Account message. This just gives us a little more space.
	 * @param object $wp_admin_bar Instance of the Admin Bar
	 */
	public function remove_howdy( $wp_admin_bar )
	{
		$my_account = $wp_admin_bar->get_node( 'my-account' );
		$newtitle = trim( str_replace( __( 'Howdy,' ), '', $my_account->title ) );
		$wp_admin_bar->add_node( array(
			'id' => 'my-account',
			'title' => $newtitle,
		) );
	}

	/**
	 * Internal method to retrieve configuration settings
	 * @param string $key The option key to retrieve
	 * @param mixed $default The default value to use if the key value does not exist
	 * @return mixed The configuration value if found; otherwise the $default value.
	 */
	private function _get_option( $key, $default )
	{
		$opt = DS_AdminColorBar::get_settings();
		if ( empty( $opt[$key] ) )
			return $default;
		return $opt[$key];
	}

	/**
	 * Adds the configuration page to the admin menu
	 */
	public function add_settings_menu()
	{
		$slug = add_submenu_page(
			'options-general.php',
			__( 'Admin Color Bar Settings', 'admin-color-bar' ),
			__( 'Admin Color Bar', 'admin-color-bar' ),		// displayed in menu
			'manage_options',							// capability
			self::SETTINGS_PAGE,						// menu slug
			array( $this, 'settings_page' )				// callback
		);
		return $slug;
	}

	/**
	 * Callback for the 'admin_enqueue_scripts'. Use this to enqueue the color picker.
	 */
	public function admin_enqueue_scripts( $screen = NULL )
	{
//error_log(__METHOD__.'() screen=' . var_export($screen, TRUE));

		if ( 'settings_page_' . self::SETTINGS_PAGE === $screen ) {
			wp_enqueue_style( 'wp-color-picker' );
			wp_enqueue_script( 'acb-settings', plugins_url( 'assets/js/acb-settings.js', __FILE__ ),
				array( 'wp-color-picker' ), DS_AdminColorBar::PLUGIN_VERSION, TRUE );
		}
	}

	/**
	 * Callback for the 'load_settings_page_acb_settings' action. Adds Contextual help screen information.
	 */
	public function contextual_help()
	{
		$screen = get_current_screen();
//error_log(__METHOD__.'() screen=' . var_export($screen, TRUE));
		if ( 'settings_page_' . self::SETTINGS_PAGE !== $screen->id )
			return;

		$screen->set_help_sidebar(
			'<p><strong>' . __( 'For more information:', 'admin-color-bar' ) . '</strong></p>' .
			'<p>' . sprintf(
						__( '<a href="%1$s" target="_blank">Post an issue</a> on <a href="%2$s" target="_blank">GitHub</a>.', 'admin-color-bar' ),
						esc_url( 'https://github.com/ServerPress/admin-color-bar/issues' ),
						esc_url( 'https://github.com/ServerPress/admin-color-bar' )) .
			'</p>'
		);

		$screen->add_help_tab( array(
			'id'	    => 'acb-settings-general',
			'title'	    => __( 'General', 'admin-color-bar' ),
			'content'	=>
				'<p>' . __( 'This page allows you to configure how the Admin Color Bar will appear.', 'admin-color-bar' ) . '</p>' .
				'<p>' . __( '<strong>Color</strong>: Select a background color to use for the Admin Bar.', 'admin-color-bar' ) . '</p>' .
				'<p>' . __( '<strong>Message</strong>: Enter a message to display on the right side of the Admin Bar.', 'admin-color-bar' ) . '</p>' .
				'<p>' . __( 'This tool is designed to help remind users that they\'re working on a Local, Staging or Live site. Selecting an effective Color and Message to remind you which type of site you are working on is up to you. The same Color and Message will be displayed for all users of the site.', 'admin-color-bar' ) . '</p>'
		));
	}

	/**
	 * Registers the setting sections and fields to be used on the configuration page
	 */
	public function settings_api_init()
	{
		$options = DS_AdminColorBar::get_settings();
		$default_values = array(
			'color' => '#0073AA',
			'textcolor' => '#eee',
			'message' => __( 'DEVELOPMENT WEBSITE', 'admin-color-bar' ),
		);
		// Parse option values into predefined keys, throw the rest away.
		$data = shortcode_atts( $default_values, $options );

		$section_id = 'admin-color-bar-settings';

		register_setting(
			self::SETTINGS_GROUP,						// option group, used for settings_fields()
			DS_AdminColorBar::SETTINGS_KEY,		// option name, used as key in database
			array( $this, 'validate_settings' )			// validation callback
		);

		add_settings_section(
			$section_id,									// id
			__( 'Admin Color Bar - Configuration:', 'admin-color-bar' ),	// title
			'__return_true',								// callback
			self::SETTINGS_PAGE								// option page
		);

		add_settings_field(
			'color',										// field id
			__( 'Admin Bar Background Color:', 'admin-color-bar' ),		// title
			array( $this, 'render_input_field' ),			// callback
			self::SETTINGS_PAGE,							// page
			$section_id,									// section id
			array(											// args
				'name' => 'color',
				'value' => $data['color'],
				'size' => '10',
				'description' => __( 'The color code for the desired Background Color of the Admin Bar.', 'admin-color-bar' ),
			)
		);
		add_settings_field(
			'textcolor',										// field id
			__( 'Admin Bar Text Color:', 'admin-color-bar' ),		// title
			array( $this, 'render_input_field' ),			// callback
			self::SETTINGS_PAGE,							// page
			$section_id,									// section id
			array(											// args
				'name' => 'textcolor',
				'value' => $data['textcolor'],
				'size' => '10',
				'description' => __( 'The color code for the desired Text Color of the Admin Bar.', 'admin-color-bar' ),
			)
		);

		add_settings_field(
			'message',										// field id
			__( 'Admin Bar Message:', 'admin-color-bar' ),	// title
			array( $this, 'render_input_field' ),			// callback
			self::SETTINGS_PAGE,							// page
			$section_id,									// section id
			array(											// args
				'name' => 'message',
				'value' => $data['message'],
				'size' => '45',
				'description' => __( 'The message to display within the Admin Bar.', 'admin-color-bar' ),
			)
		);
	}

	/**
	 * Renders an input field control
	 * @param array $args Array of arguments, contains name and value.
	 */
	public function render_input_field($args)
	{
		$attrib = '';
		if ( isset( $args['size'] ) )
			$attrib = ' size="' . esc_attr( $args['size'] ) . '" ';
		if ( ! empty( $args['class'] ) )
			$attrib .= ' class="' . esc_attr( $args['class'] ) . '" ';
		if ( ! empty( $args['placeholder'] ) )
			$attrib .= ' placeholder="' . esc_attr( $args['placeholder'] ) . '" ';

		printf( '<input type="text" id="acb-form-%s" name="'. DS_AdminColorBar::SETTINGS_KEY . '[%s]" value="%s" %s />',
			$args['name'], $args['name'], esc_attr( $args['value'] ), $attrib );

		if ( ! empty( $args['description'] ) )
			echo '<p><em>', esc_html( $args['description'] ), '</em></p>';
	}

	/**
	 * Output settings page contents
	 */
	public function settings_page()
	{
		echo '<div class="wrap ds-color-bar-settings">';
		echo '<h1>', __( 'Configuration Settings for Admin Color Bar', 'admin-color-bar' ), '</h1>';
		echo '<h3 style="margin-top:0">', __( 'by ServerPress, LLC', 'admin-color-bar' ), '</h3>';
		echo '<form id="form-acb" action="options.php" method="POST">';
		settings_fields(self::SETTINGS_GROUP);
		do_settings_sections(self::SETTINGS_GROUP);
		submit_button();
		echo '</form>';
		echo '</div>';
	}

	/**
	 * Validates the values submitted via the form
	 * @param array $values The submitted form values
	 * @return array validated form contents
	 */
	public function validate_settings( $values )
	{
		$settings = DS_AdminColorBar::get_settings();
		if ( ! current_user_can( 'manage_options' ) )
			return $settings;

		$out = array_merge( $settings, array() );

		foreach ($values as $key => $value) {
			switch ( $key ) {
			case 'color':
				if ( $this->_is_valid_hex_code( $value ) )
					$out[$key] = $value;
				break;

			case 'textcolor':
				if ( $this->_is_valid_hex_code( $value ) )
					$out[$key] = $value;
				break;

			case 'message':
				$out[$key] = strip_tags( $value );
				break;
			}
		}

		return $out;
	}

	/**
	 * Check if string contains a valid color hex code in the form of #hhhhhh
	 * @param string $code The data to validate
	 * @return boolean TRUE if the string contains a hex code; otherwise FALSE
	 */
	private function _is_valid_hex_code( $code )
	{
		if ( '#' !== substr( $code, 0, 1 ) )
			return FALSE;
		$hex = strtolower( substr( $code, 1 ) );
		if ( 3 !== strlen( $hex ) && 6 !== strlen( $hex ) )
			return FALSE;

		$allowed = '0123456789abcdef';
		for ( $idx = 0; $idx < strlen( $hex ); ++$idx ) {
			$ch = substr( $hex, $idx, 1 );
			if ( FALSE === strpos( $allowed, $ch ) )
				return FALSE;
		}
		return TRUE;
	}
}

DS_AdminColorBarAdmin::get_instance();

// EOF