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
	public function __construct() {

		global $af_required;

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
		add_filter( 'genesis_attr_title-area', array( $this, 'class_cell_title_area' ), 11 );
		add_filter( 'af4_before_nav', array( $this, 'change_menu_toggle' ), 10 );
		add_filter( 'af4_before_nav', array( $this, 'change_title_bar_cell' ), 10 );
		remove_filter( 'af4_before_nav', array( $af_required, 'add_search_toggle' ), 11 );

		// Move right header widget area attached to the AgriFlex\RequiredDOM class.
		add_filter( 'wp_nav_menu_args', array( $this, 'nav_menu_args' ) );
		add_filter( 'af4_top_bar_left_attr', array( $this, 'af4_top_bar_left_attr' ) );
		add_filter( 'af4_header_right_attr', array( $this, 'af4_header_right_attr' ) );
		remove_filter( 'af4_before_nav', array( $af_required, 'add_search_toggle' ), 11 );
		remove_action( 'genesis_header', array( $af_required, 'add_header_right_widgets' ), 10 );
		add_filter( 'af4_primary_nav_menu', array( $this, 'add_search_widget' ), 9 );

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
		preg_match('/^<[a-z]+[^>]*>/', $site_title, $title_open );
		preg_match('/<\/[a-z]+>$/', $site_title, $title_close );
		$site_title = $title_open[0] . $new_inside . $title_close[0];

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

		// Get array of images.
		preg_match_all( '/<img[^>]+>/', $title, $images, PREG_SET_ORDER );

		// Find out if the user has already chosen their mobile logo.
		$has_mobile_logo_already = false;

		// Hide existing logo(s) for mobile unless they are a mobile logo already.
		foreach ( $images as $image ) {
			$new_image = $image[0];
			// Get class property.
			preg_match( '/class="([^"]*)"/', $image[0], $img_class );
			// Update class property.
			if ( strpos( $image[0], 'class=' ) && preg_match( '/show-for-small(-only)?/', $img_class[1], $vis_class ) ) {
				// User-uploaded logo for small or all screens.
				$has_mobile_logo_already = true;
				$new_image = '<span class="cell shrink alunit-mobile-logo ' . $vis_class[0] . '">' . $new_image . '</span>';
				$title = str_replace( $image[0], $new_image, $title );
				continue;
			} else {
				if ( false === empty( $img_class ) && false === strpos( $img_class[1], 'hide-for-small-only' ) && false === strpos( $img_class[1], 'show-for-medium' ) ) {
					// Image is already hidden on small screens.
					continue;
				}
				if ( empty( $img_class ) ) {
					// Image does not have a class property.
					$new_image = str_replace( '<img', '<img class="hide-for-small-only"', $new_image );
				} else {
					$img_class[1] = 'hide-for-small-only';
					$new_image = str_replace( $img_class[0], 'class="' . $img_class[1] . '"', $new_image );
				}
			}
			// Replace old image code with new code.
			$title = str_replace( $image[0], $new_image, $title );
		}

		// Add new logo and title for mobile.
		$mobile_title = sprintf(
			'<span class="cell auto site-title-text show-for-small-only">%s</span>',
			get_bloginfo( 'name ')
		);
		if ( ! $has_mobile_logo_already ) {
			$mobile_title = sprintf( '<span class="cell shrink alunit-mobile-logo show-for-small-only"><img src="%simages/AgriLife-A.png"></span>', ALUAF4_DIR_URL ) . $mobile_title;
		}
		// Remove inline width and height attributes from images.
		$title = preg_replace( '/ (width|height)="[^"]*"/', '', $title );
		// Add grid class to anchor tag.
		$title = str_replace( '<a ', '<a class="grid-x" ', $title );
		// Add mobile title to the end of the anchor tag.
		$title = str_replace( '</a>', $mobile_title . '</a>', $title );

		return $title;

	}

	/**
	 * Add header title area cell class names
	 *
	 * @since 1.2.4
	 * @param array $attributes HTML attributes.
	 * @return array
	 */
	public function class_cell_title_area( $attributes ) {

		$attributes['class'] = str_replace( 'small-6 medium-2', 'auto medium-shrink', $attributes['class'] );
		return $attributes;

	}

	/**
	 * Change header menu toggle
	 *
	 * @since 1.2.4
	 * @param string $output Output for af4_before_nav_args.
	 * @return string
	 */
	public function change_menu_toggle( $output = '' ) {

		$output = str_replace( '<div class="title-bar-title" data-toggle="nav-menu-primary">Menu</div>', '', $output );
		return $output;

	}

	/**
	 * Change title bar cell class.
	 *
	 * @since 1.2.4
	 * @param string $output Output for af4_before_nav_args.
	 * @return string
	 */
	public function change_title_bar_cell( $output = '' ) {

		$output = str_replace( '<div class="title-bars cell small-6', '<div class="title-bars cell shrink', $output );
		return $output;

	}

	/**
	 * Add search widget and toggle button.
	 *
	 * @since 1.2.4
	 * @param string $output Output for the primary menu.
	 * @return string
	 */
	public function add_search_widget( $output ) {

		global $af_required;

		$search  = '<div class="title-bars cell medium-shrink title-bar-right">';
		$search .= '<div class="title-bar title-bar-search"><button class="search-icon" type="button" data-toggle="header-search"></button><div class="title-bar-title">Search</div>';
		$search  = $af_required->add_header_right_widgets( $search );
		$search  = str_replace( 'id="header-search', 'data-toggler=".hide-for-medium" id="header-search', $search );
		$search .= '</div></div>';

		return $output . $search;

	}

	/**
	 * Change attributes for header right widget area
	 *
	 * @since 1.2.7
	 * @param array $attributes HTML attributes.
	 * @return array
	 */
	public function af4_header_right_attr( $attributes ) {
		$attributes['class'] = 'header-right-widget-area hide-for-medium';
		return $attributes;
	}

	/**
	 * Change class for primary nav menu
	 *
	 * @since 1.2.9
	 * @param array $args Arguments for menu.
	 * @return array
	 */
	public function nav_menu_args( $args ) {

		if ( 'primary' === $args['theme_location'] ) {

			$args['menu_class'] .= ' cell medium-auto';

		}

		return $args;

	}

	/**
	 * Change attributes for top bar left
	 *
	 * @since 1.2.9
	 * @param array $attributes HTML attributes.
	 * @return array
	 */
	public function af4_top_bar_left_attr( $attributes ) {
		$attributes['class'] .= ' grid-x grid-padding-x';
		return $attributes;
	}

	/**
	 * Return instance of class
	 *
	 * @since 1.2.7
	 * @return object.
	 */
	public static function get_instance() {

		return null === self::$instance ? new self() : self::$instance;

	}

}
