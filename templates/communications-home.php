<?php
/**
 * The file that renders the Communications site's home page content
 *
 * A custom page template for Communications - Home
 *
 * @link       https://github.com/AgriLife/af4-agrilife-unit/blob/master/templates/communications-home.php
 * @since      0.1.0
 * @package    af4-agrilife-unit
 * @subpackage af4-agrilife-unit/templates
 */

/**
 * Template Name: Communications - Home
 */
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
add_action( 'wp_enqueue_scripts', 'af4_unit_fonts' );
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
add_action( 'genesis_after_entry', 'agrilife_unit_com_home' );

/**
 * Registers all styles used within the plugin
 *
 * @since 0.1.0
 * @return void
 */
function af4_unit_fonts() {

	wp_register_style(
		'agrilife-unit-googlefonts',
		'https://fonts.googleapis.com/css?family=Oswald',
		array(),
		'1.0.0'
	);

	wp_enqueue_style( 'agrilife-unit-googlefonts' );

}

/**
 * Output content of template.
 *
 * @since 0.1.0
 * @return void
 */
function agrilife_unit_com_home() {

	$output = '<div id="home-com">';

	// Services block.
	$services      = get_field( 'service_group' );
	$services_list = '';

	foreach ( $services['categories'] as $service ) {

		$services_list .= sprintf(
			'<div class="service"><div class="image"><img src="%s" alt="%s"></div><div class="wrap"><h3>%s</h3><p class="description">%s</p><a href="%s" target="%s">%s</a></div></div>',
			$service['image']['sizes']['medium_large'],
			$service['image']['alt'],
			$service['title'],
			$service['description'],
			$service['button']['url'],
			$service['button']['target'],
			$service['button']['title']
		);

	}

	$output .= sprintf(
		'<div id="services" class="layout-container flow-arrow"><div class="row">%s</div></div>',
		$services_list
	);

	// Resources block.
	$resources       = get_field( 'resource_group' );
	$resources_title = '';
	$resources_list  = '';

	if ( ! empty( $resources ) ) {

		$resources_title = "<h2>{$resources['title']}</h2>";
		$agency_nicename = array(
			'agrilife'  => 'AgriLife',
			'extension' => 'AgriLife Extension',
			'research'  => 'AgriLife Research',
			'coals'     => 'College of Agriculture & Life Sciences',
			'tvmdl'     => 'TVMDL',
			'forest'    => 'Forest Service',
		);

		foreach ( $resources['agency'] as $agency ) {

			$resources_list .= sprintf(
				'<div class="agency"><div class="logo center-line"><img src="%s" alt="%s"></div><a href="%s" target="%s">%s</a></div>',
				AF_THEME_DIRURL . "/images/logos/{$agency['name']}-white.png",
				$agency_nicename[ $agency['name'] ],
				$agency['button']['url'],
				$agency['button']['target'],
				$agency['button']['title']
			);

		}
	}

	$output .= sprintf(
		'<div id="resources"><div class="layout-container">%s<div class="row">%s</div></div></div>',
		$resources_title,
		$resources_list
	);

	$output .= '</div>';

	echo wp_kses_post( $output );
}

genesis();
