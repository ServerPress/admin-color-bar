<?php
/**
 * Plugin Name: Admin Color Bar
 * Plugin URL: https://serverpress.com/plugins/admin-bar-color
 * Description: Changes the Admin bar color
 * Version: 1.2
 * Author: Gregg Franklin
 * Author URI: http://greggfranklin.com
 * Text Domain: admin-color-bar
 * Domain path: /language
 */

class DS_AdminColorBar
{
	private static $_instance = NULL;
	private static $_settings = NULL;

	const SETTINGS_KEY = 'admin-color-bar-options';

	const PLUGIN_NAME = 'Admin Color Bar';
	const PLUGIN_VERSION = '1.2';

	private function __construct()
	{
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
	}

	public static function get_instance()
	{
		if ( NULL === self::$_instance )
			self::$_instance = new self();
		return self::$_instance;
	}

	public static function get_settings()
	{
		if ( NULL === self::$_settings )
			self::$_settings = get_option( self::SETTINGS_KEY, array() );
		return self::$_settings;
	}

	public function admin_menu()
	{
		if ( is_admin_bar_showing() ) {
			require_once( dirname( __FILE__ ) . '/admin-color-bar-admin.php' );
		}
	}
}

DS_AdminColorBar::get_instance();

// EOF