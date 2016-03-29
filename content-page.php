<?php
/**
 * The template used for displaying page content
 *
 * @package WordPress
 * @subpackage japieventos
 * @since japieventos 1.0
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

	<?php if (has_excerpt()) : ?>
	<div class="entry-excerpt">
		<?php the_excerpt(); ?>
	</div>
	<?php endif; ?>

	<div id="top-page" class="title">
		<div  class="inner">
			<?php the_title( '<header class="entry-header"><h1 class="entry-title">', '</h1></header><!-- .entry-header -->' ); 
			?>
			
			<?php if (has_excerpt()) : ?>
			<div class="entry-excerpt">
				<h2><?php the_excerpt(); ?></h2>
			</div>
			<?php endif; ?>
		</div>
		
		

	</div>

	<div class="entry-content">
		<div class="inner">
			<div class="columns medium-9">
			<?php
				the_content();
				wp_link_pages( array(
					'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'japieventos' ) . '</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
				) );
	
				edit_post_link( __( 'Edit', 'japieventos' ), '<span class="edit-link">', '</span>' );
			?>
			</div>
		</div>
	</div><!-- .entry-content -->
	
</article><!-- #post-## -->
