<?php
/**
 * A page template for Service Landing Pages
 *
 * @link       https://github.com/AgriLife/agriflex4/blob/master/templates/service-landing-page.php
 * @since      0.7.0
 * @package    agriflex4
 * @subpackage agriflex4/templates
 */

/**
 * Template Name: Service Landing Page
 */
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );
add_action( 'genesis_after_entry', 'af4_service_landing_page' );

add_filter( 'genesis_attr_entry', 'af4_genesis_attributes_entry' );

// Conditionally show page title.
if ( false === get_field( 'heading_group' )['show_page_title'] ) {
	remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
}

// Register styles used in the page template.
add_action( 'wp_enqueue_scripts', 'af4_service_register_styles' );

// Enqueue styles used in the page template.
add_action( 'wp_enqueue_scripts', 'af4_service_enqueue_styles' );

/**
 * Add attributes for entry element.
 *
 * @since 0.7.1
 *
 * @param array $attributes Existing attributes.
 *
 * @return array Amended attributes.
 */
function af4_genesis_attributes_entry( $attributes ) {
	$attributes['class'] .= ' grid-container';
	return $attributes;
}

/**
 * Register page template styles
 *
 * @since 0.7.0
 * @return void
 */
function af4_service_register_styles() {

	wp_register_style(
		'agriflex-service-lp-styles',
		AF_THEME_DIRURL . '/css/service-landing-page.css',
		array(),
		filemtime( AF_THEME_DIRPATH . '/css/service-landing-page.css' ),
		'screen'
	);

}

/**
 * Enqueue page template styles
 *
 * @since 0.7.0
 * @return void
 */
function af4_service_enqueue_styles() {

	wp_enqueue_style( 'agriflex-service-lp-styles' );

}

/**
 * Output content of template.
 *
 * @since 0.7.0
 * @return void
 */
function af4_service_landing_page() {

	$sections = get_field( 'sections' );
	$output   = '<div class="service-landing">';

	if ( is_array( $sections ) ) {

		foreach ( $sections as $section ) {

			if ( array_key_exists( 'acf_fc_layout', $section ) ) {
				$type = $section['acf_fc_layout'];
			} else {
				$type = '';
			}

			// Create classes for section.
			$section_type_class = str_replace( '_', '-', $type );
			$classes            = array(
				'section',
				$section_type_class,
				"columns-{$section['columns']}",
			);

			if ( 'none' !== $section['background_color'] ) {
				$classes[] = 'bg-' . $section['background_color'];
			}

			if ( true === $section['arrow_group']['show'] ) {
				$classes[] = 'flow-arrow';
				if ( 'none' !== $section['arrow_group']['border_color'] ) {
					$classes[] = 'flow-arrow-bdr-' . $section['arrow_group']['border_color'];
				}
			}

			// Create section title.
			$title_output = '';
			if ( ! empty( $section['title']['text'] ) ) {
				$title_class  = '';
				$title_output = '<h2%s>%s</h2>';
				if ( 'none' !== $section['title']['underline_color'] ) {
					$title_class = " class=\"underline underline-{$section['title']['underline_color']}\"";
				}
				$title_output = sprintf(
					$title_output,
					$title_class,
					$section['title']['text']
				);
			}

			// Create row output.
			$repeater_output = '';

			// Item section specific output.
			if ( 'item_section' === $type ) {

				$type = $section['items_group']['type'];
				// Remove empty items.
				$items = array();
				foreach ( $section['items_group'][ $type ] as $item ) {
					$filtered = array_filter( $item );
					if ( ! empty( $filtered ) ) {
						$items[] = $item;
					}
				}

				if ( 'card' === $type ) {
					// Create cards items.
					foreach ( $items as $key => $item ) {
						// Image.
						$image = '';
						if ( ! empty( $item['image'] ) ) {
							$image = sprintf(
								'<div class="image"><img src="%s" alt="%s"></div>',
								$item['image']['sizes']['medium_large'],
								$item['image']['alt']
							);
						}

						// Title.
						$title = '';
						if ( ! empty( $item['title'] ) ) {
							$title = "<h3 class=\"underline underline-lightgreen\">{$item['title']}</h3>";
						}

						// Description.
						$desc = '';
						if ( ! empty( $item['description'] ) ) {
							$desc = "<div class=\"description\">{$item['description']}</div>";
						}

						// Button.
						$button = '';
						if ( ! empty( $item['button'] ) ) {
							$button = sprintf(
								'<a class="item-button" href="%s" target="%s">%s</a>',
								$item['button']['url'],
								$item['button']['target'],
								$item['button']['title']
							);
						}

						// Assemble item.
						$repeater_output .= sprintf(
							'<div class="cell card">%s<div class="wrap">%s%s%s</div></div>',
							$image,
							$title,
							$desc,
							$button
						);
					}
				} elseif ( 'flowchart' === $type ) {
					// Create flowchart items.
					foreach ( $items as $key => $item ) {
						// Heading.
						$heading = '';
						$hfields = $item['heading_group'];
						if ( ! empty( $hfields ) ) {
							$htype   = $hfields['type'];
							$hchoice = $hfields[ $htype ];
							$hinside = '';
							if ( ! empty( $hchoice ) ) {
								switch ( $htype ) {
									case 'text':
										$hinside = sprintf(
											'<span class="text">%s</span>',
											$hfields['text']
										);
										break;
									case 'image':
										$hinside = sprintf(
											'<img src="%s" alt="%s">',
											$hfields['image']['sizes']['medium_large'],
											$hfields['image']['alt']
										);
										break;
									case 'logo':
										$logo_nicename = array(
											'agrilife'  => 'AgriLife',
											'extension' => 'AgriLife Extension',
											'research'  => 'AgriLife Research',
											'coals'     => 'College of Agriculture & Life Sciences',
											'tvmdl'     => 'TVMDL',
											'forest'    => 'Forest Service',
										);
										$hinside       = sprintf(
											'<img class="logo" src="%s/images/logos/%s-%s.png" alt="%s">',
											AF_THEME_DIRURL,
											$hfields['logo'],
											$hfields['logo_color'],
											$logo_nicename[ $hfields['logo'] ]
										);
										break;
									default:
										break;
								}

								$heading = sprintf(
									'<div class="heading center-line line-%s">%s</div>',
									$item['line_color'],
									$hinside
								);
							}
						}

						// Button.
						$button = '';
						if ( ! empty( $item['button'] ) ) {
							$button = sprintf(
								'<a class="item-button" href="%s" target="%s">%s</a>',
								$item['button']['url'],
								$item['button']['target'],
								$item['button']['title']
							);
						}

						// Assemble item.
						$repeater_output .= sprintf(
							'<div class="cell flowchart-item">%s%s</div>',
							$heading,
							$button
						);
					}
				}
			}

			$output .= sprintf(
				'<div class="%s"><div class="grid-container">%s<div class="cells %s">%s</div></div></div>',
				implode( ' ', $classes ),
				$title_output,
				$section['items_group']['align'],
				$repeater_output
			);
		}
	}

	$output .= '</div>';

	echo wp_kses_post( $output );
}

genesis();
