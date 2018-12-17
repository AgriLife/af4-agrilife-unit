<?php

namespace Agrilife_Unit;

/**
 * Sets up Genesis Framework to our needs
 * @package Agrilifedotorg
 * @since 0.1.0
 */
class Genesis {

	public function __construct() {

		// Remove header widget area
		unregister_sidebar( 'header-right' );

		// Relocate primary navigation menu
		remove_action( 'genesis_after_header', 'genesis_do_nav' );
		add_action( 'genesis_header', 'genesis_do_nav' );

		// Replace site title with logo
		add_filter( 'genesis_seo_title', array( $this, 'add_logo' ), 10, 3 );

	}

	public function add_logo( $title, $inside, $wrap ){

		$logo = sprintf( '<img src="%s">', AF_THEME_DIRURL . '/images/logo-agrilife.png' );

		$new_inside = sprintf( '<a href="%s" title="Texas A&M AgriLife">%s</a>',
			trailingslashit( home_url() ),
			$logo
		);

		$title = str_replace( $inside, $new_inside, $title );

		return $title;

	}

}
