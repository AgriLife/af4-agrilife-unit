<?php
/**
 * AgriLife Unit - AgriFlex4
 *
 * @package      Agrilife
 * @author       Zachary Watkins
 * @copyright    2019 Texas A&M AgriLife Communications
 * @license      GPL-2.0+
 *
 * @wordpress-plugin
 * Plugin Name:  AgriLife Unit - AgriFlex4
 * Plugin URI:   https://github.com/AgriLife/af4-agrilife-unit
 * Description:  Core functionality for AgriLife Unit sites on AgriFlex4.
 * Version:      0.3.3
 * Author:       Zachary Watkins
 * Author URI:   https://github.com/ZachWatkins
 * Author Email: zachary.watkins@ag.tamu.edu
 * Text Domain:  af4-agrilife-unit
 * License:      GPL-2.0+
 * License URI:  http://www.gnu.org/licenses/gpl-2.0.txt
 */

/* Define some useful constants */
define( 'ALUAF4_DIRNAME', 'af4-agrilife-unit' );
define( 'ALUAF4_DIR_PATH', plugin_dir_path( __FILE__ ) );
define( 'ALUAF4_DIR_FILE', __FILE__ );
define( 'ALUAF4_DIR_URL', plugin_dir_url( __FILE__ ) );
define( 'ALUAF4_TEXTDOMAIN', 'af4-agrilife-unit' );
define( 'ALUAF4_TEMPLATE_PATH', ALUAF4_DIR_PATH . 'templates' );

/* Code for plugins */
register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
register_activation_hook( __FILE__, 'af4_agrilife_activation' );

/**
 * Helper option flag to indicate rewrite rules need flushing
 *
 * @since 0.1.0
 * @return void
 */
function af4_agrilife_activation() {
	if ( ! get_option( 'af4_agrilife_flush_rewrite_rules_flag' ) ) {
		add_option( 'af4_agrilife_flush_rewrite_rules_flag', true );
	}
}

/**
 * The core plugin class that is used to initialize the plugin
 */
require_once ALUAF4_DIR_PATH . '/src/class-agrilife-unit.php';

/* Autoload all classes */
spl_autoload_register( 'Agrilife_Unit::autoload' );
Agrilife_Unit::get_instance();
