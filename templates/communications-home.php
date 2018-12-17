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
	?><div id="overview">
		<div id="leaders-heading" class="layout-container"><h1><span class="heading-1">Leaders in </span><span class="heading-2">Agriculture,<br> Natural Resources,<br> &amp; Life Sciences</span></h1></div>
		<div id="action-items" class="layout-container">
			<div class="item item-1"><div class="wrap"><h2>86th Legislature</h2>
				<div class="description has-line hide-for-small-only"><ul><li><span class="show-for-xlarge">Texas A&M AgriLife </span>Extension<span class="show-for-medium"> Service</span></li>
				<li><span class="show-for-xlarge">Texas A&M AgriLife </span>Research</li>
				<li><span class="show-for-xlarge">Texas A&M </span>Veterinary<span class="hide-for-medium hide-for-large"> Medical</span> Diagnostic<span class="hide-for-xlarge">s</span><span class="show-for-xlarge"> Laboratory</span></li>
				<li><span class="show-for-xlarge">Texas A&M </span>Forest<span class="show-for-medium hide-for-large">ry</span><span class="show-for-xlarge"> Service</span></li></ul></div></div>
			</div>
			<div class="item item-2"><img class="hide-for-small-only" src="<?php echo ALUAF4_DIR_URL; ?>/images/home-about.jpg" alt=""><h2>What is <br>Texas A&M AgriLife?</h2></div>
			<div class="item  item-3 featured"><div class="wrap" style="background-image: url(<?php echo ALUAF4_DIR_URL; ?>/images/home-impacts.jpg);"><img src="<?php echo ALUAF4_DIR_URL; ?>/images/home-impacts.jpg" alt=""><h2>Impacts</h2>
				<div class="description has-line hide-for-small-only">Residents, AgriLife Extension, others work to 'Harvey-proof' Houston-area community</div>
			</div></div>
			<div class="item item-4"><img class="hide-for-small-only" src="<?php echo ALUAF4_DIR_URL; ?>/images/home-today.jpg" alt=""><h2>AgriLife Today</h2>
				<div class="description has-line hide-for-small-only">Texas A&M AgriLife to lead consortium in establishing Center
			</div></div>
			<div class="item item-5">
				<div class="wrap"><h2><span class="big">October Newsletter</span></h2><!-- Begin Mailchimp Signup Form -->
					<div id="mc_embed_signup" class="description">
						<form action="https://agrilife.us1.list-manage.com/subscribe/post?u=1a8a0ed97f45319b1319f2572&amp;id=449a7707a3" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
					    <div id="mc_embed_signup_scroll">
								<div class="mc-field-group">
									<label for="mce-EMAIL" class="screen-reader-text">Email Address </label>
									<input type="email" value="" placeholder="Email" name="EMAIL" class="required email" id="mce-EMAIL">
								</div>
								<div id="mce-responses" class="clear">
									<div class="response" id="mce-error-response" style="display:none"></div>
									<div class="response" id="mce-success-response" style="display:none"></div>
								</div>    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
						    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_1a8a0ed97f45319b1319f2572_449a7707a3" tabindex="-1" value=""></div>
						    <div class="clear"><input type="submit" value="Sign up" name="subscribe" id="mc-embedded-subscribe" class="button"></div>
					    </div>
						</form>
					</div><script type='text/javascript' src='//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js'></script><script type='text/javascript'>(function($) {window.fnames = new Array(); window.ftypes = new Array();fnames[0]='EMAIL';ftypes[0]='email';fnames[1]='FNAME';ftypes[1]='text';fnames[2]='LNAME';ftypes[2]='text';fnames[3]='ADDRESS';ftypes[3]='address';fnames[4]='PHONE';ftypes[4]='phone';}(jQuery));var $mcj = jQuery.noConflict(true);</script><!--End mc_embed_signup-->
				</div>
			</div>
		</div>
	</div>
	<div id="agencies" class="flowchart no-arrow">
		<div class="layout-container">
			<div class="flowchart-row top"><div class="al item"><a href="https://agrilife.org/" title="Texas A&M AgriLife"><span><img src="<?php echo AF_THEME_DIRURL; ?>/images/logo-tamu-agrilife.png" alt="Texas A&M AgriLife"></span></a></div></div>
			<div class="flowchart-row bottom"><div class="ext item"><a href="http://agrilifeextension.tamu.edu/" title="Texas A&M AgriLife Extension Service"><span><img class="hide-for-small-only" src="<?php echo AF_THEME_DIRURL; ?>/images/logo-tamu-agrilife-extension.png" alt="Texas A&M AgriLife Extension Service"><span class="show-for-small-only">Texas A&amp;M AgriLife Extension</span></span></a></div><div class="res item"><a href="http://agriliferesearch.tamu.edu/" title="Texas A&M AgriLife Research"><span><img class="hide-for-small-only" src="<?php echo AF_THEME_DIRURL; ?>/images/logo-tamu-agrilife-research.png" alt="Texas A&M AgriLife Research"><span class="show-for-small-only">Texas A&amp;M AgriLife Research</span></span></a></div><div class="college item"><a href="http://aglifesciences.tamu.edu/" title="Texas A&M College of Agrculture and Life Sciences"><span><img class="hide-for-small-only" src="<?php echo AF_THEME_DIRURL; ?>/images/logo-tamu-coals-vertical-white-small.png" alt="Texas A&M College of Agrculture and Life Sciences"><span class="show-for-small-only">Texas A&amp;M University College of Agriculture &amp; Life Sciences</span></span></a></div><div class="tvmdl item"><a href="http://tvmdl.tamu.edu/" title="Texas A&M Veterinary Medical Diagnostics Laboratory"><span><img class="hide-for-small-only" src="<?php echo AF_THEME_DIRURL; ?>/images/logo-tamu-tvmdl.png" alt="Texas A&M Veterinary Medical Diagnostics Laboratory"><span class="show-for-small-only">Texas A&amp;M Veterinary Medical Diagnostic Laboratory</span></span></a></div><div class="tfs item"><a href="http://texasforestservice.tamu.edu/" title="Texas A&M Forest Service"><span><img class="hide-for-small-only" src="<?php echo AF_THEME_DIRURL; ?>/images/logo-tamu-forest-service.png" alt="Texas A&M Forest Service"><span class="show-for-small-only">Texas A&amp;M Forest Service</span></span></a></div></div>
		</div>
	</div>
	<?php
});

genesis();
