<?php
/**
 * The template for displaying 404 pages (Not Found).
 *
 * @package WordPress
 * @subpackage japieventos
 * @since japieventos 1.0
 */

get_header(); ?>

<div id="primary" class="content-area row">

		<div id="content" class="site-content columns medium-9" role="main">
		
			<article style="margin:30px 0 120px;" id="post-0" class="post error404 not-found" role="main">
				<h1 class="entry-title"><?php _e( 'This is somewhat embarrassing, isn&rsquo;t it?', 'japieventos' ); ?></h1>
				<p><?php _e( 'It appears the page you are looking for does not exist. Perhaps searching, or one of the links below, can help.', 'japieventos' ); ?></p>

			</article>
			
		</div><!-- #content -->
		
		<?php //get_sidebar(); ?>
	</div><!-- #primary -->
	

<?php get_footer(); ?>
