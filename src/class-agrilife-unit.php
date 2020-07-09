<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/AgriLife/af4-agrilife-unit/blob/master/src/class-agrilifeunit.php
 * @since      1.0.0
 * @package    af4-agrilife-unit
 * @subpackage af4-agrilife-unit/src
 */

/**
 * The core plugin class
 *
 * @since 0.1.0
 * @return void
 */
class Agrilife_Unit {

	/**
	 * File name
	 *
	 * @var file
	 */
	private static $file = __FILE__;

	/**
	 * Instance
	 *
	 * @var instance
	 */
	private static $instance;

	/**
	 * Initialize the class
	 *
	 * @since 0.1.0
	 * @return void
	 */
	private function __construct() {

		add_action( 'init', array( $this, 'init' ) );

		$this->register_templates();

		// Add ACF WYSIWYG toolbar.
		add_filter( 'acf/fields/wysiwyg/toolbars', array( $this, 'toolbars' ) );

	}

	/**
	 * Initialize the various classes
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function init() {

		// Set up asset files.
		require_once ALUAF4_DIR_PATH . '/src/class-assets.php';
		$ado_assets = new \Agrilife_Unit\Assets();

		// Get Genesis set up the way we want it.
		require_once ALUAF4_DIR_PATH . '/src/class-genesis.php';
		$ado_genesis = new \Agrilife_Unit\Genesis();

		// Add custom post type for Exceptional Items.
		if ( class_exists( 'acf' ) ) {
			require_once ALUAF4_DIR_PATH . 'fields/communications-home-fields.php';
		}

		if ( get_option( 'af4_agrilife_flush_rewrite_rules_flag' ) ) {
			flush_rewrite_rules();
			delete_option( 'af4_agrilife_flush_rewrite_rules_flag' );
		}

	}

	/**
	 * Add ACF toolbars
	 *
	 * @since 0.1.0
	 * @param array $toolbars The list of toolbars.
	 * @return array
	 */
	public function toolbars( $toolbars ) {

		// Add new toolbars.
		$toolbars['Simple Text']     = array();
		$toolbars['Simple Text'][1]  = array( 'formatselect', 'bold', 'italic', 'underline', 'link', 'unlink', 'alignleft', 'aligncenter', 'alignjustify', 'bullist', 'numlist' );
		$toolbars['Simple Title']    = array();
		$toolbars['Simple Title'][1] = array( 'bold', 'italic', 'underline' );

		return $toolbars;

	}

	/**
	 * Initialize page templates
	 *
	 * @since 0.1.0
	 * @return void
	 */
	private function register_templates() {

		require_once ALUAF4_DIR_PATH . '/src/class-pagetemplate.php';
		$com_home = new \Agrilife_Unit\PageTemplate( ALUAF4_TEMPLATE_PATH, 'communications-home.php', 'Communications - Home' );
		$com_home->register();

		$template_service = new \Agrilife_Unit\PageTemplate( ALUAF4_TEMPLATE_PATH, 'service-landing-page.php', 'Service Landing Page' );
		$template_service->register();

	}

	/**
	 * Autoloads any classes called within the theme
	 *
	 * @since 0.1.0
	 * @param string $classname The name of the class.
	 * @return void
	 */
	public static function autoload( $classname ) {

		$filename = dirname( __FILE__ ) .
			DIRECTORY_SEPARATOR .
			str_replace( '_', DIRECTORY_SEPARATOR, $classname ) .
			'.php';
		if ( file_exists( $filename ) ) {
			require $filename;
		}

	}

	/**
	 * Return instance of class
	 *
	 * @since 0.1.0
	 * @return object.
	 */
	public static function get_instance() {

		return null === self::$instance ? new self() : self::$instance;

	}

}
