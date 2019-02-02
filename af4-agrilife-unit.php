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
require_once ALUAF4_DIR_PATH . '/src/class-agrilife-unit.php';
spl_autoload_register( 'Agrilife_Unit::autoload' );
Agrilife_Unit::get_instance();
