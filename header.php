<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage japieventos
 * @since japieventos 1.0
 */
?><!DOCTYPE html>
  <html <?php language_attributes(); ?> >
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>" />
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?php wp_title( '|', true, 'right' ); ?></title>
			
	    <meta name="description" content="<?php echo '' . get_bloginfo ( 'description' );  ?>">
	    <meta name="robots" content="index,follow">
		<link rel="profile" href="http://gmpg.org/xfn/11" />
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/normalize.css" />
		<link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/foundation.min.css" />
		<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
		<!--[if lt IE 9]>
			<script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
		<![endif]-->
<?php
		/* We add some JavaScript to pages with the comment form
		 * to support sites with threaded comments (when in use).
		 */
		if ( is_singular() && get_option( 'thread_comments' ) )
			wp_enqueue_script( 'comment-reply' );

		/* Always have wp_head() just before the closing </head>
		 * tag of your theme, or you will break many plugins, which
		 * generally use this hook to add elements to <head> such
		 * as styles, scripts, and meta tags.
		 */
		wp_head();
?>
	</head>
	
	

	<body <?php body_class(); ?>>

		<div class="clearfix" id="page">
				
			<header id="header">
				
				<?php global $japieventos_options;
					$japieventos_settings = get_option( 'japieventos_options', $japieventos_options ); ?>
		
				<div id="top">
					<div class="row">
						<ul id="social">
			                <li class="face"><a target="_blank" href="http://facebook.com/japijane">Facebook</a></li>
			                <li class="tw"><a target="_blank" href="http://twitter.com/japijane">Twitter</a></li>
			                <li class="ins"><a target="_blank" href="http://instagram.com/japijane">Instagram</a></li>
			                <li class="yt"><a target="_blank" href="http://www.youtube.com/user/japijane/videos">Youtube</a></li>
			             </ul>
						<nav id="nav-2">
						 <?php  wp_nav_menu( array( 'container_id' => 'menu-secondary', 'theme_location' => 'secondary', 'sort_column' => 'menu_order' ) ); ?>
						</nav>
					</div>
				</div>
				
				<div class="row">
					
					<?php if( $japieventos_settings['custom_logo'] ) : ?>
					<a id="logo" href="<?php echo bloginfo('url'); ?>" class="logo">
						<img src="<?php echo $japieventos_settings['custom_logo']; ?>" alt="<?php bloginfo('name'); ?>" /> 
					</a>
					<?php  else : ?>
					<h1 id="logo"><a href="<?php echo bloginfo('url'); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>"><?php bloginfo( 'name' ); ?></a>
					</h1>
				    <?php endif; ?>
					
					<a class="open" href="javascript:void(0)"><span></span>MENU</a>
					
					<nav id="access" role="navigation" class="">
						<a class="screen-reader-text skip-link" href="#content"><?php _e( 'Skip to content', 'japieventos' ); ?></a>
						<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
					</nav><!-- #access -->

				</div>
				
				<div id="bottom">
					<div class="row">
						<p class="show-for-large-up">Nuestros Eventos:</p>
						<?php wp_nav_menu( array( 'walker' => new description_walker(), 'menu_class' => 'navigation','container' => false, 'theme_location' => 'third' ) ); 
						
						?>
					</div>
				</div>
		
			</header>

			<div id="main" role="main">
				 			