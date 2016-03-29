<?php
/**
 * @package WordPress
 * @subpackage japieventos
 * @since japieventos 1.0
 */

get_header(); ?>

<div id="primary" class="content-area">
		<div id="content" class="site-content page-default row" role="main">
		
		<?php // include('inc/breadcrumbs.php'); ?>

			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();

					// Include the page content template.
					get_template_part( 'content', 'page' );

				endwhile;
			?>

		</div>
	</div>

<?php get_footer(); ?>