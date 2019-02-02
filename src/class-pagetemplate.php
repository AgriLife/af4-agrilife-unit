<?php
/**
 * The file that registers a page template
 *
 * A class definition that includes functions and filters which register a page template.
 *
 * @link       https://github.com/AgriLife/af4-agrilife-unit/blob/master/src/class-pagetemplate.php
 * @since      1.0.0
 * @package    af4-agrilife-unit
 * @subpackage af4-agrilife-unit/src
 */

namespace Agrilife_Unit;

/**
 * The Page Template initialization class
 *
 * @since 0.1.0
 * @return void
 */
class PageTemplate {

	/**
	 * File name
	 *
	 * @var file
	 */
	protected $file;

	/**
	 * Template folder path
	 *
	 * @var path
	 */
	protected $path;

	/**
	 * Template full path
	 *
	 * @var full_path
	 */
	protected $full_path;

	/**
	 * Template name
	 *
	 * @var name
	 */
	protected $name;

	/**
	 * Associative array of template file path to template name
	 *
	 * @var templates
	 */
	protected $templates;

	/**
	 * Initialize page templates
	 *
	 * @since 0.1.0
	 * @param string $path File path to template folder.
	 * @param string $file Template file name.
	 * @param string $name Proper name of template.
	 * @throws \Exception Path must be valid and file cannot be missing.
	 * @return void
	 */
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

			$this->name = wp_strip_all_tags( $name );

		}

		$this->templates = array(
			$this->file => $this->name,
		);

	}

	/**
	 * Get file path
	 *
	 * @since 0.1.0
	 * @return string
	 */
	public function get_path() {

		return $this->path;

	}

	/**
	 * Get full template file path
	 *
	 * @since 0.1.0
	 * @return string
	 */
	public function get_file() {

		return $this->file;

	}

	/**
	 * Define template file
	 *
	 * @since 0.1.0
	 * @param string $file File name.
	 * @throws \Exception File name must exist.
	 * @return string
	 */
	public function with_file( $file ) {

		$full_path = $this->path . '/' . $file . '.php';

		if ( empty( $file ) ) {
			throw new \Exception( 'The filename cannot be blank' );
		} elseif ( ! file_exists( $full_path ) ) {
			throw new \Exception( 'The template file must exist' );
		} else {
			$this->file = $file . '.php';

			return $this;
		}

	}

	/**
	 * Get file name
	 *
	 * @since 0.1.0
	 * @return string
	 */
	public function get_name() {

		return $this->name;

	}

	/**
	 * Register page template to dashboard
	 *
	 * @since 0.1.0
	 * @return array
	 */
	public function register() {

		if ( version_compare( floatval( $GLOBALS['wp_version'] ), '4.7', '<' ) ) {
			$filters['dropdown'] = add_filter( 'page_attributes_dropdown_pages_args', array( $this, 'add_to_cache' ) );
		} else {
			$filter['dropdown'] = add_filter( 'theme_page_templates', array( $this, 'add_to_cache_templates' ) );
		}

		$filters['admin_init']       = add_filter( 'admin_init', array( $this, 'add_to_cache' ) );
		$filters['post_data']        = add_filter( 'wp_insert_post_data', array( $this, 'add_to_cache' ) );
		$filters['template_include'] = add_filter( 'template_include', array( $this, 'view_project_template' ) );

		return $filters;

	}

	/**
	 * Add template to cache of theme page templates
	 *
	 * @since 0.1.0
	 * @param array $templates List of page templates.
	 * @return array
	 */
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

	/**
	 * Add template to cache of theme page templates
	 *
	 * @since 0.1.0
	 * @param array $atts Cache attributes.
	 * @return array
	 */
	public function add_to_cache( $atts ) {

		$cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

		// Extract templates if using WordPress <4.7.
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

	/**
	 * Add template to template_include
	 *
	 * @since 0.1.0
	 * @param string $template Template.
	 * @return string
	 */
	public function view_project_template( $template ) {

		// Get global post.
		global $post;

		// Return template if post is empty.
		if ( ! $post ) {
			return $template;
		}

		// Return default template if we don't have a custom one defined.
		$template_meta = get_post_meta(
			$post->ID,
			'_wp_page_template',
			true
		);
		if ( ! isset( $this->templates[ $template_meta ] ) ) {
			return $template;
		}

		$file = $this->path . '/' . $template_meta;

		// Just to be safe, we check if the file exist first.
		if ( file_exists( $file ) ) {
			return $file;
		} else {
			echo esc_url( $file );
		}

		// Return template.
		return $template;

	}

}
