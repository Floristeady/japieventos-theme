<?php
/**
 * Template Name: P&aacute;gina de Inicio
 * @package WordPress
 * @subpackage japieventos
 * @since japieventos 1.0
 */

get_header(); ?>

<section id="content" class="site-content row list-events" role="main">

		<?php
		$current= get_the_id($post->ID);
		$my_id = $wpdb->get_var("SELECT ID FROM $wpdb->posts WHERE post_name = 'reservas'");;
		$args = array(
			'order'=>'ASC',
			'orderby'=> 'menu_order',
			'exclude' => $current,
			'parent' => 0,
			'offset' => 0,
			'post_status' => 'publish',
			'post_type' => 'page',
			'post__not_in' =>  array($current,$my_id)
		);
		
		query_posts( $args );
		

		?>
		
		<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<?php static $count = 1; ?>
		
		<article id="slide-<?php echo  $count ?>" class="slide" data-slide="<?php echo  $count ?>" >
			
			<div class="inner">
				
				<div class="content-title columns medium-8">
					<h1 class="entry-title"><?php the_title(); ?></h1>
					
					<?php if (has_excerpt()) : ?>
					<div class="entry-excerpt columns medium-11">
						<h2><?php the_excerpt(); ?></h2>
					</div>
					<?php endif; ?>
				</div>
	
				<div class="entry-content columns medium-offset-1  medium-6">
					<?php the_content(); ?>
					<?php if( get_field('informacion_extra') ): ?>
						<h3><?php the_field('informacion_extra'); ?></h3>
					<?php endif;?>
					
					<?php if( get_field('link_boton') ): ?>
					<a class="button" href="<?php the_field('link_boton'); ?>"><?php the_field('texto_boton'); ?></a>
					<?php endif;?>
					
				</div>
	
				<div class="medium-5 columns thumbnail">
					<div class="image-article">
					<?php japieventos_post_thumbnail(); ?>
					</div>
				</div>
			
			</div>
			
		</article>	
		<?php $count++; ?>

		<?php 
			endwhile; endif;
			wp_reset_query(); 
		?>
		
</section><!-- #content -->

<?php get_footer(); ?>