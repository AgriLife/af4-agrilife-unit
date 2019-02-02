<?php
/**
 * The file that provides required public DOM elements for the plugin
 *
 * A class definition that inserts DOM where needed in the theme
 *
 * @link       https://github.com/AgriLife/af4-agrilife-unit/blob/master/src/class-requireddom.php
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
class RequiredDOM {

	/**
	 * Initialize the class
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function __construct() {

		add_filter( 'af4_before_nav', array( $this, 'add_search_toggle' ), 10, 4 );

		// Add search form after navigation menu.
		add_action( 'genesis_header', array( $this, 'add_search_form' ) );

	}

	/**
	 * Add header search and menu toggle
	 *
	 * @since 0.1.0
	 * @param string $content Content of 'af4_before_nav' variable in theme.
	 * @param string $open    Content of the element's open tag.
	 * @param string $close   Content of the element's close tag.
	 * @param string $inside  Content of the element's inner HTML.
	 * @return string
	 */
	public function add_search_toggle( $content, $open, $close, $inside ) {

		$search = '<div class="title-bar title-bar-search" data-responsive-toggle="header-search"><button class="search-icon" type="button" data-toggle="header-search"></button><div class="title-bar-title">Search</div></div>';

		$content = $open . $search . $inside . $close;

		return $content;

	}

	/**
	 * Add header search form
	 *
	 * @since 0.1.0
	 * @return void
	 */
	public function add_search_form() {

		echo '<div id="header-search">';
		get_search_form();
		echo '</div>';

	}
}
