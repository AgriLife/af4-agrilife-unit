<?php
/**
 * The file that hooks into the Genesis framework
 *
 * A class definition that includes hooks dependent on the Genesis framework
 *
 * @link       https://github.com/AgriLife/af4-agrilife-unit/blob/master/src/class-genesis.php
 * @since      1.0.0
 * @package    af4-agrilife-unit
 * @subpackage af4-agrilife-unit/src
 */

namespace Agrilife_Unit;

/**
 * Set up Genesis Framework to our needs
 *
 * @package af4-agrilife-unit
 * @since 0.1.0
 */
class Genesis {

	/**
	 * Initialize the class
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function __construct() {

		add_theme_support(
			'custom-header',
			array(
				'width'  => 1400,
				'height' => 225,
			)
		);

		// Add Unit Header Content.
		add_filter( 'genesis_structural_wrap-header', array( $this, 'unit_header' ), 999 );

		// Add logo and site title for mobile.
		add_filter( 'af4_header_logo', array( $this, 'genesis_seo_title' ), 11, 3 );

	}

	/**
	 * Output the unit header
	 *
	 * @since 0.1.0
	 * @param string $output The output of the Genesis header structural wrap.
	 * @return string
	 */
	public function unit_header( $output ) {

		ob_start();
		genesis_seo_site_title();
		$site_title = ob_get_clean();
		$new_inside = sprintf( '<a href="%s">%s</a>', trailingslashit( home_url() ), get_bloginfo( 'name' ) );
		$site_title = preg_replace( '/<a [^<]+<img [^<]+<\/a>/', $new_inside, $site_title );

		ob_start();
		genesis_seo_site_description();
		$site_description = ob_get_clean();

		$unit_header = sprintf(
			'<div class="unit-header-wrap" style="background-image:url(%s);"><div class="unit-header layout-container"><div class="wrap">%s</div></div></div>',
			get_header_image(),
			"{$site_title}{$site_description}"
		);

		$output = preg_replace( '/<\/div>$/', '</div>' . $unit_header, $output );

		return $output;

	}

	/**
	 * Change site title content.
	 *
	 * @since 1.2.0
	 * @param string $title Genesis SEO title html.
	 * @param string $inside The inner HTML of the title.
	 * @param string $wrap The tag name of the seo title wrap element.
	 * @return string
	 */
	public function genesis_seo_title( $title, $inside, $wrap ) {

		// Hide main logo on mobile.
		$replacement = 'class="show-for-medium agrilife-logo';
		$title = str_replace( 'class="agrilife-logo', $replacement, $title );

		$mobile_title = sprintf(
			'<img class="agrilife-mobile-logo custom-logo show-for-small-only" src="%s/images/AgriLife-A.png"><span class="site-title-text hide-for-medium">%s</span>',
			ALUAF4_DIR_URL,
			get_bloginfo( 'name ')
		);
		$title = str_replace( '</a>', $mobile_title . '</a>', $title );

		return $title;

	}

}
