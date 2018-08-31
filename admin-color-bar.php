<?php
/**
 * Plugin Name: Admin Color Bar
 * Plugin URL: https://serverpress.com/plugins/admin-bar-color
 * Description: Changes the Admin bar color
 * Version: 1.2.2
 * Author: Gregg Franklin, Dave Jesch
 * Author URI: http://ServerPress.com
 * Text Domain: admin-color-bar
 * Domain path: /language
 */

class DS_AdminColorBar
{
	private static $_instance = NULL;
	private static $_settings = NULL;

	const SETTINGS_KEY = 'admin-color-bar-options';

	const PLUGIN_NAME = 'Admin Color Bar';
	const PLUGIN_VERSION = '1.2.2';

	private function __construct()
	{
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );
		add_action( 'wp_head', array( $this, 'footer' ) );
		add_action( 'admin_bar_menu', array( $this, 'admin_bar_notice' ) );
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
	public function footer()
	{
		$settings = self::get_settings();
		$color = empty( $settings['color'] ) ? '#0073AA' : $settings['color'];
		$textcolor = empty( $settings['textcolor'] ) ? '#eee' : $settings['textcolor'];
?>
<style type='text/css'>
	#wp-admin-bar-environment-notice>div,
	#wpadminbar { background-color: <?php echo $color; ?> !important }
	#wp-admin-bar-environment-notice { display: none }
	@media only screen and (min-width: 1030px) {
	    #wp-admin-bar-environment-notice { display: block }
	    #wp-admin-bar-environment-notice > div > span {
	        /*color: #EEE !important;*/
	        font-size: 130% !important;
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

	public function admin_bar_notice()
	{
		$settings = self::get_settings();
		$message = empty( $settings['message'] ) ? 'DEVELOPMENT WEBSITE' : $settings['message'];

		$admin_notice = array(
			'parent'	=> 'top-secondary',						// puts it on the right side
			'id'		=> 'environment-notice',
			'title'		=> '<span>' . esc_html( $message ) . '</span>',
		);

		global $wp_admin_bar;
		$wp_admin_bar->add_menu( $admin_notice );
	}
}

DS_AdminColorBar::get_instance();

// EOF
