<?php
/**
 * The file that defines css and js files loaded for the plugin
 *
 * A class definition that includes css and js files used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://github.com/AgriLife/af4-agrilife-unit/blob/master/src/class-assets.php
 * @since      1.0.0
 * @package    af4-agrilife-unit
 * @subpackage af4-agrilife-unit/src
 */

namespace Agrilife_Unit;

/**
 * Add assets
 *
 * @package af4-agrilife-unit
 * @since 0.1.0
 */
class Assets {

		/**
		 * Initialize the class
		 *
		 * @since 0.1.0
		 * @return void
		 */
	public function __construct() {

		// Register global styles used in the theme.
		add_action( 'admin_footer', array( $this, 'register_admin_styles' ) );

		// Register global styles used in the theme.
		add_action( 'wp_enqueue_scripts', array( $this, 'register_styles' ) );

		// Enqueue extension styles.
		add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );

	}

	/**
	 * Registers all admin styles used within the plugin
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function register_admin_styles() {
		wp_register_style(
			'agrilife-admin-styles',
			ALUAF4_DIR_URL . 'css/admin.css',
			array(),
			filemtime( ALUAF4_DIR_PATH . 'css/admin.css' ),
			'screen'
		);

		wp_enqueue_style( 'agrilife-admin-styles' );
	}

	/**
	 * Registers all styles used within the plugin
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function register_styles() {

		global $wp_query;
		$template_name = get_post_meta( $wp_query->post->ID, '_wp_page_template', true );

		wp_register_style(
			'agrilife-styles',
			ALUAF4_DIR_URL . 'css/agrilife-unit.css',
			array( 'agriflex-default-styles', 'agriflex-default-template-styles' ),
			filemtime( ALUAF4_DIR_PATH . 'css/agrilife-unit.css' ),
			'screen'
		);

		wp_register_style(
			'agrilife-com-styles',
			ALUAF4_DIR_URL . 'css/communications.css',
			array(),
			filemtime( ALUAF4_DIR_PATH . 'css/communications.css' ),
			'screen'
		);

		if ( ! $template_name || 'default' === $template_name ) {
			wp_register_style(
				'agrilife-default-template-styles',
				ALUAF4_DIR_URL . 'css/template-default.css',
				array( 'agrilife-styles' ),
				filemtime( ALUAF4_DIR_PATH . 'css/template-default.css' ),
				'screen'
			);
		}

	}

	/**
	 * Enqueues extension styles
	 *
	 * @since 0.1.0
	 * @global $wp_styles
	 * @return void
	 */
	public function enqueue_styles() {

		global $wp_query;
		$template_name = get_post_meta( $wp_query->post->ID, '_wp_page_template', true );

		wp_enqueue_style( 'agrilife-styles' );

		wp_enqueue_style( 'agrilife-communications-styles' );

		if ( ! $template_name || 'default' === $template_name ) {
			wp_enqueue_style( 'agrilife-default-template-styles' );
		}

	}

}
