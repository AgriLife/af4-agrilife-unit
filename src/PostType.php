<?php
namespace Agrilife_Unit;

/**
 * Builds and registers a custom post type.
 * @package Photo Gallery Post Type
 * @since 1.0.0
 */
class PostType {

	private $post_type;
	private $single_file = false;
	private $archive_file = false;
	private $search_file = false;

	/**
	 * Builds and registers the custom taxonomy.
	 * @param  array  $name       The post type name.
	 * @param  string $slug       The post type slug.
	 * @param  string $tag        The namespace of the plugin for translation purposes.
	 * @param  array  $taxonomies The taxonomies this post type supports. Accepts arguments found in
	 *                            WordPress core register_post_type function.
	 * @param  string $icon       The icon used in the admin navigation sidebar.
	 * @param  array  $supports   The attributes this post type supports. Accepts arguments found in
	 *                            WordPress core register_post_type function.
	 * @return void
	 */
	public function __construct( $name = array( 'singular' => '', 'plural' => ''), $path, $slug, $tag, $taxonomies = array( 'category', 'post_tag' ), $icon = 'dashicons-portfolio', $supports = array( 'title' ), $templates = array() ) {

		$this->post_type = $slug;

		if( array_key_exists( 'single', $templates ) ){
			$this->single_file = $templates['single'];
		}

		if( array_key_exists( 'archive', $templates ) ){
			$this->archive_file = $templates['archive'];
		}

		if( array_key_exists( 'search', $templates ) ){
			$this->search_file = $templates['search'];
		}

		$singular = $name['singular'];
		$plural = $name['plural'];

		// Backend labels
		$labels = array(
			'name' => __( $plural, $tag ),
			'singular_name' => __( $plural, $tag ),
			'add_new' => __( 'Add New', $tag ),
			'add_new_item' => __( 'Add New ' . $singular, $tag ),
			'edit_item' => __( 'Edit ' . $singular, $tag ),
			'new_item' => __( 'New ' . $singular, $tag ),
			'view_item' => __( 'View ' . $singular, $tag ),
			'search_items' => __( 'Search ' . $plural, $tag ),
			'not_found' => __( 'No ' . $plural . ' Found', $tag ),
			'not_found_in_trash' => __( 'No ' . $plural . ' found in trash', $tag ),
			'parent_item_colon' => '',
			'menu_name' => __( $plural, $tag ),
		);

		// Post type arguments
		$args = array(
			'can_export' => true,
			'has_archive' => true,
			'labels' => $labels,
			'menu_icon' => $icon,
			'menu_position' => 20,
			'public' => true,
			'publicly_queryable' => true,
			'show_in_rest' => true,
			'show_in_menu' => true,
			'show_in_admin_bar' => true,
			'show_in_nav_menus' => true,
			'show_ui' => true,
			'supports' => $supports,
			'taxonomies' => $taxonomies
		);

		// Register the post type
		register_post_type( $slug, $args );

		// Register the post type templates
		$post_template = new \Agrilife\Templates( $path, $slug, $this->single_file, $this->archive_file, $this->search_file );

	}

}
