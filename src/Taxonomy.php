<?php
namespace Agrilife_Unit;

/**
 * Builds and registers a custom taxonomy.
 * @package AgriLife Majors and Degrees
 * @since 1.0.0
 */
class Taxonomy {

	protected $slug;
	protected $meta_boxes = array();
	protected $template;

	/**
	 * Builds and registers the custom taxonomy.
	 * @param  string $name      The taxonomy name.
	 * @param  string $slug      The taxonomy slug.
	 * @param  string $post_slug The slug of the post type where the taxonomy will be added.
	 * @param  string $tag       The plugin namespace for translations.
	 * @param  array  $user_args The arguments for taxonomy registration. Accepts $args from the
	 *                           WordPress core register_taxonomy function.
	 * @param  array  $meta      Array (single or multidimensional) of custom fields to add to a taxonomy item
	 *                           edit page. Requires 'name', 'slug', and 'type'.
	 * @param  string $template  The template file path for the taxonomy archive page.
	 * @return void
	 */
	public function __construct($name, $slug, $post_slug, $tag, $user_args = array(), $meta = array(), $template = '') {

		$this->slug = $slug;

		$singular = $name;
		$plural = $name . 's';

		// Taxonomy labels
		$labels = array(
			'name' => __( $plural, $tag ),
			'singular_name' => __( $singular, $tag ),
			'search_items' => __( 'Search ' . $plural, $tag ),
			'all_items' => __( 'All ' . $plural, $tag ),
			'parent_item' => __( 'Parent ' . $singular, $tag ),
			'parent_item_colon' => __( 'Parent ' . $singular . ':', $tag ),
			'edit_item' => __( 'Edit ' . $singular, $tag ),
			'update_item' => __( 'Update ' . $singular, $tag ),
			'add_new_item' => __( 'Add New ' . $singular, $tag ),
			'new_item_name' => __( 'New ' . $singular . ' Name', $tag ),
			'menu_name' => __( $plural, $tag ),
		);

		// Taxonomy arguments
		$args = array_merge(
			array(
				'labels' => $labels,
				'hierarchical' => true,
				'show_ui' => true,
				'show_admin_column' => true,
				'rewrite' => array( 'slug' )
			),
			$user_args
		);

		// Register the Type taxonomy
		register_taxonomy( $slug, $post_slug, $args );

		// Create taxonomy custom fields
		// Ensure meta is an array of one or more arrays
		if( !empty($meta) ){
			if( !is_array($meta[0]) ){
				$this->meta_boxes[] = $meta;
			} else {
				foreach ($meta as $key => $value) {
					$this->meta_boxes[] = $value;
				}
			}
			$this->add_meta_box();
		}

		// Add custom template for post taxonomies
		if( !empty( $template ) ){
			$this->template = $template;
			add_filter( 'template_include', array( $this, 'custom_template' ) );
		}

	}

	/**
	 * Add actions to render and save custom fields
	 * @return void
	 */
	public function add_meta_box(){
  	add_action( "{$this->slug}_edit_form_fields", array($this, 'taxonomy_edit_meta_field'), 10, 2 );
	  add_action( "edited_{$this->slug}", array($this, 'save_taxonomy_custom_meta'), 10, 2 );
	}

	/**
	 * Render custom fields
	 * @param object $term     Current taxonomy term object
	 * @param string $taxonomy Current taxonomy slug
	 * @return void
	 */
  public function taxonomy_edit_meta_field( $tag, $taxonomy ) {

    // put the term ID into a variable
    $t_id = $tag->term_id;

    foreach ($this->meta_boxes as $key => $meta) {
	    // retrieve the existing value(s) for this meta field. This returns an array
	    $slug = $meta['slug'];
	    $term_meta = get_option( "taxonomy_{$t_id}_{$slug}" );

	    ?><tr class="form-field term-<?php echo $slug; ?>-wrap">
        <th scope="row" valign="top"><label for="term_meta_<?php echo $slug; ?>"><?php _e( $meta['name'], 'mjd' ); ?></label></th>
        <td>
          <?php

          $value = $term_meta ? stripslashes( $term_meta ) : '';
          $value = html_entity_decode( $value );

          switch ($meta['type']) {
          	case 'full':
          		wp_editor( $value, 'term_meta_' . $slug, array(
          			'textarea_name' => 'term_meta_' . $slug,
          			'wpautop' => false
          		) );
          		break;

          	case 'link':
          		?><input type="url" name="term_meta_<?php echo $slug; ?>" id="term_meta_<?php echo $slug; ?>" value="<?php echo $value; ?>" placeholder="https://example.com" pattern="http[s]?://.*"><?php
          		break;

          	default:
          		?><input type="text" name="term_meta_<?php echo $slug; ?>" id="term_meta_<?php echo $slug; ?>" value="<?php echo $value; ?>"><?php
          		break;
          }

          ?>
          <p class="description"><?php _e( 'Enter a value for this field','mjd' ); ?></p>
        </td>
	  	</tr><?php
    }
  }

	/**
	 * Save custom fields
	 * @param int $term_id The term ID
	 * @param int $tt_id   The term taxonomy ID
	 * @return void
	 */
  public function save_taxonomy_custom_meta( $term_id, $tt_id ) {

  	foreach ($this->meta_boxes as $key => $meta) {

  		$slug = $meta['slug'];

	    if ( isset( $_POST['term_meta_' . $slug] ) ) {

	      $t_id = $term_id;
	      $value = $_POST['term_meta_' . $slug];
	      $value = sanitize_text_field( htmlentities( $value ) );

	      // Save the option array.
	      update_option( "taxonomy_{$t_id}_{$slug}", $value );

	    }

  	}

  }

	/**
	 * Use custom template file if on the taxonomy archive page
	 * @param string $template The path of the template to include
	 * @return string
	 */
  public function custom_template( $template ){

  	if( is_tax( $this->slug ) ){

  		return $this->template;

  	}

  	return $template;
  }

}
