<?php
/**
 * Plugin Name: AgriLife Unit - AgriFlex4
 * Plugin URI: https://github.com/AgriLife/af4-agrilife-unit
 * Description: Core functionality for AgriLife Unit sites on AgriFlex4
 * Version: 0.1.0
 * Author: Zachary Watkins
 * Author URI: https://github.com/ZachWatkins/
 * Author Email: zachary.watkins@ag.tamu.edu
 * License: GPL-2.0+
 */

require 'vendor/autoload.php';

// Define some useful constants
define( 'ALUAF4_DIRNAME', 'af4-agrilife-unit' );
define( 'ALUAF4_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'ALUAF4_DIR_FILE', __FILE__ );
define( 'ALUAF4_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'ALUAF4_TEXTDOMAIN', 'af4-agrilife-unit' );
define( 'ALUAF4_TEMPLATE_PATH', ALUAF4_DIR_PATH . 'templates' );

// Code for plugins
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
register_activation_hook( __FILE__, 'af4_agrilife_activation' );
function af4_agrilife_activation() {
	if ( ! get_option( 'af4_agrilife_flush_rewrite_rules_flag' ) ) {
		add_option( 'af4_agrilife_flush_rewrite_rules_flag', true );
	}
}

// Autoload all classes
spl_autoload_register( 'Agrilife_Unit::autoload' );

class Agrilife_Unit {

	private static $file = __FILE__;

	private static $instance;

	private function __construct() {

		add_action( 'init', array( $this, 'init' ) );

		$this->register_templates();

		// Add ACF WYSIWYG toolbar
		add_filter( 'acf/fields/wysiwyg/toolbars' , array( $this, 'toolbars' ) );

	}

	/**
	 * Initialize the various classes
	 * @since 0.1.0
	 * @return void
	 */
	public function init() {

		// Set up asset files
		$ado_assets = new \Agrilife_Unit\Assets;

		// Get Genesis set up the way we want it
		$ado_genesis = new \Agrilife_Unit\Genesis;

		// Set up required DOM
		$ado_dom = new \Agrilife_Unit\RequiredDOM;

		// Add custom post type for Exceptional Items
	  if ( class_exists( 'acf' ) ) {
	    // require_once(ALUAF4_DIR_PATH . 'fields/exceptional-item-fields.php');
	  }

		if ( get_option( 'af4_agrilife_flush_rewrite_rules_flag' ) ) {
			flush_rewrite_rules();
			delete_option( 'af4_agrilife_flush_rewrite_rules_flag' );
		}

	}

	/**
	 * Add ACF toolbars
	 * @since 0.1.0
	 * @return void
	 */
	function toolbars( $toolbars ) {

		// Add new toolbars
		$toolbars['Simple Text'] = array();
		$toolbars['Simple Text'][1] = array( 'formatselect', 'bold' , 'italic', 'underline', 'link', 'unlink', 'alignleft', 'aligncenter', 'alignjustify', 'bullist', 'numlist' );

		$toolbars['Simple Title'] = array();
		$toolbars['Simple Title'][1] = array( 'bold' , 'italic', 'underline' );

		return $toolbars;

	}

	/**
	 * Initialize page templates
	 * @since 0.1.0
	 * @return void
	 */
	private function register_templates() {

		$com_home = new \Agrilife_Unit\PageTemplate( ALUAF4_TEMPLATE_PATH, 'communications-home.php', 'Communications - Home' );
		$com_home->register();

	}

	/**
	 * Autoloads any classes called within the theme
	 * @since 0.1.0
	 * @param  string $classname The name of the class
	 * @return void
	 */
	public static function autoload( $classname ) {

		$filename = dirname( __FILE__ ) .
      DIRECTORY_SEPARATOR .
      str_replace( '_', DIRECTORY_SEPARATOR, $classname ) .
      '.php';
    if ( file_exists( $filename ) )
      require $filename;

	}

	public static function get_instance() {

		return null == self::$instance ? new self : self::$instance;

	}

}

Agrilife_Unit::get_instance();
