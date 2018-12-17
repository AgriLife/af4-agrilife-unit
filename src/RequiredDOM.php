<?php

namespace Agrilife_Unit;

/**
 * Add assets
 * @package Agrilife.org
 * @since 0.1.0
 */
class RequiredDOM {

  public function __construct() {

  	add_filter( 'af4_before_nav', array( $this, 'add_search_toggle' ), 10, 4 );

		// Add search form after navigation menu
		add_action( 'genesis_header', array( $this, 'add_search_form' ) );

  }

  public function add_search_toggle( $content, $open, $close, $inside ){

		$search = '<div class="title-bar title-bar-search" data-responsive-toggle="header-search"><button class="search-icon" type="button" data-toggle="header-search"></button><div class="title-bar-title">Search</div></div>';

		$content = $open . $search . $inside . $close;

		return $content;

  }

	public function add_search_form(){

		?><div id="header-search"><?php
		ob_start();
		get_search_form();
		$search = ob_get_clean();
		echo $search;
		?></div><?php

	}
}
