<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage japieventos
 * @since japieventos 1.0
 */

get_header(); ?>

	
	
	<div id="primary" class="content-area row">
		<?php include('inc/breadcrumbs.php'); ?>
		
		<div id="content" class="site-content columns medium-9" role="main">

		

			<?php
				// Start the Loop.
				while ( have_posts() ) : the_post();

					/*
					 * Include the post format-specific template for the content. If you want to
					 * use this in a child theme, then include a file called called content-___.php
					 * (where ___ is the post format) and that will be used instead.
					 */
					get_template_part( 'content', get_post_format() );

					// Previous/next post navigation.
					japieventos_post_nav();

				endwhile;
			?>

		</div>
		
		<?php get_sidebar(); ?>
		
	</div>

<?php get_footer(); ?>
