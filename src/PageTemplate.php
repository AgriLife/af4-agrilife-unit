<?php

namespace Agrilife_Unit;

class PageTemplate {

	protected $file;

	protected $path;

	protected $full_path;

	protected $name;

	protected $templates;

	public function __construct( $path = null, $file = null, $name = null ) {

		if ( empty( $path ) ) {

			throw new \Exception( 'The path cannot be blank' );

		} elseif ( ! is_dir( $path ) ) {

			throw new \Exception( 'The path must exist' );

		} else {

			$this->path = $path;

		}

		if ( empty( $file ) ) {

			throw new \Exception( 'The filename cannot be blank' );

		} else {

			$this->full_path = $path . '/' . $file;

			if ( ! file_exists( $this->full_path ) ) {

				throw new \Exception( 'The template file must exist' );

			} else {

				$this->file = $file;

			}

		}

		if ( empty( $file ) ) {

			throw new \Exception( 'The name cannot be blank' );

		} else {

			$this->name = strip_tags( $name );

		}

		$this->templates = array(
			$this->file => $this->name
		);

	}

	public function get_path() {

		return $this->path;

	}

	public function get_file() {

		return $this->file;

	}

	public function with_file( $file ) {

		$full_path = $this->path . '/' . $file . '.php';

		if ( empty( $file ) ) {
			throw new \Exception( 'The filename cannot be blank' );
		} elseif ( ! file_exists( $full_path ) ) {
			throw new \Exception( 'The template file must exist' );
		} else {
			// $this->file = $full_path;
			$this->file = $file . '.php';

			return $this;
		}

	}

	public function get_name() {

		return $this->name;

	}

	public function register() {

		if ( version_compare( floatval($GLOBALS['wp_version']), '4.7', '<' ) ) {
			$filters['dropdown']       = add_filter( 'page_attributes_dropdown_pages_args', array( $this, 'add_to_cache' ) );
		} else {
			$filter['dropdown']        = add_filter( 'theme_page_templates', array( $this, 'add_to_cache_templates' ) );
		}

		$filters['admin_init']       = add_filter( 'admin_init', array( $this, 'add_to_cache' ) );
		$filters['post_data']        = add_filter( 'wp_insert_post_data', array( $this, 'add_to_cache' ) );
		$filters['template_include'] = add_filter( 'template_include', array( $this, 'view_project_template' ) );

		return $filters;

	}

	public function add_to_cache_templates( $templates ) {

		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

		if ( empty( $templates ) ) {
			$templates = array();
		}

		wp_cache_delete( $cache_key, 'themes' );

		$new_template = array( $this->file => $this->name );

		$templates = array_merge( $templates, $new_template );

		wp_cache_add( $cache_key, $templates, 'themes', 1800 );

		return $templates;

	}

	public function add_to_cache( $atts ) {

		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

		// Extract templates if using WordPress <4.7
		$templates = wp_get_theme()->get_page_templates();

		if ( empty( $templates ) ) {
			$templates = array();
		}

		wp_cache_delete( $cache_key, 'themes' );

		$new_template = array( $this->file => $this->name );

		$templates = array_merge( $templates, $new_template );

		wp_cache_add( $cache_key, $templates, 'themes', 1800 );

		return $atts;

	}

	public function view_project_template( $template ) {

		// Get global post
		global $post;

		// Return template if post is empty
		if ( ! $post ) {
			return $template;
		}

		// Return default template if we don't have a custom one defined
		if ( ! isset( $this->templates[get_post_meta(
			$post->ID, '_wp_page_template', true
		)] ) ) {
			return $template;
		}

		$file = $this->path . '/' . get_post_meta(
			$post->ID, '_wp_page_template', true
		);

		// Just to be safe, we check if the file exist first
		if ( file_exists( $file ) ) {
			return $file;
		} else {
			echo $file;
		}

		// Return template
		return $template;

	}

}
