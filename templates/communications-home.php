<?php
/**
 * Template Name: Home
 */
remove_action( 'genesis_entry_header', 'genesis_do_post_title' );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_open', 5 );
remove_action( 'genesis_entry_header', 'genesis_entry_header_markup_close', 15 );
add_action( 'wp_head', function(){
	?><link href="https://fonts.googleapis.com/css?family=Oswald" rel="stylesheet"><?php
});
add_filter( 'genesis_pre_get_option_site_layout', '__genesis_return_full_width_content' );

add_action('genesis_entry_content', function(){
	?><div id="home-com">
	<div id="services" class="layout-container flow-arrow"><div class="row"><?php

		$services = get_field('service_group');

		foreach ($services['categories'] as $service) {

			echo sprintf( '<div class="service"><div class="image"><img src="%s" alt="%s"></div><div class="wrap"><h3>%s</h3><p class="description">%s</p><a href="%s" target="%s">%s</a></div></div>',
				$service['image']['sizes']['medium_large'],
				$service['image']['alt'],
				$service['title'],
				$service['description'],
				$service['button']['url'],
				$service['button']['target'],
				$service['button']['title']
			);

		}

	?></div></div>
	<div id="resources"><div class="layout-container"><?php

			$resources = get_field('resource_group');

			if( !empty($resources) ){

				echo "<h2>{$resources['title']}</h2>";

				$agency_nicename = array(
					'agrilife' => 'AgriLife',
					'extension' => 'AgriLife Extension',
					'research' => 'AgriLife Research',
					'coals' => 'College of Agriculture & Life Sciences',
					'tvmdl' => 'TVMDL',
					'forest' => 'Forest Service'
				);

				?><div class="row"><?php

				foreach ($resources['agency'] as $agency) {

					echo sprintf( '<div class="agency"><div class="logo center-line"><img src="%s" alt="%s"></div><a href="%s" target="%s">%s</a></div>',
						AF_THEME_DIRURL . "/images/logos/{$agency['name']}-white.png",
						$agency_nicename[$agency['name']],
						$agency['button']['url'],
						$agency['button']['target'],
						$agency['button']['title']
					);

				}

			}

		?>
		</div></div></div>
	</div>
	<?php
});

genesis();
