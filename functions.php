<?php
/**
 * japieventos functions and definitions
 *
 * The first function, japieventos_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * For information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage japieventos
 * @since japieventos 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 1024;

/** Tell WordPress to run japieventos_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'japieventos_setup' );

if ( ! function_exists( 'japieventos_setup' ) ):

/**
 * Set up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support post thumbnails.
 */
function japieventos_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style( array( 'css/editor-style.css', japieventos_font_url() ) );
	
	// Create Theme Logotype Options Page
    require_once ( get_template_directory() . '/theme-admin/theme-options.php' );
    
	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );
	
	// Enable support for Post Thumbnails, and declare two sizes.
	add_theme_support( 'post-thumbnails' );
	set_post_thumbnail_size( 450, 450, true );
	add_image_size( 'featured-page', 450, 450, true);

	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'japieventos', TEMPLATEPATH . '/languages' );

	$locale = get_locale();
	$locale_file = TEMPLATEPATH . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Navegación Principal', 'japieventos' ),
		'secondary' => __( 'Navegación Secundaria', 'japieventos' ),
		'third' => __( 'Navegación Páginas', 'japieventos' ),
	) );
	
	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list',
	) );
	
	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 
	add_theme_support( 'post-formats', array(
		'image', 'video', 'audio', 'quote', 'link', 'gallery',
	) );*/
	
	// This theme allows users to set a custom background.
	add_theme_support( 'custom-background', apply_filters( 'japieventos_custom_background_args', array(
		'default-color' => 'ffffff',
	) ) );
	
	// This theme allows users to set a custom header
	global $wp_version;
	if ( version_compare( $wp_version, '3.4', '>=' ) )
		add_theme_support( 'custom-header' );
	else
		add_custom_image_header( $args );
		
	$defaults = array(
	'random-default'         => false,
	'width'                  => 970,
	'height'                 => 220,
	'flex-height'            => true,
	'flex-width'             => false,
	'default-text-color'     => 'fff',
	'header-text'            => false,
	'uploads'                => true,
	'wp-head-callback'       => '',
	'admin-head-callback'    => '',
	'admin-preview-callback' => '',
	);
	add_theme_support( 'custom-header', $defaults );
	}
endif;


/**
 * Create a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since japieventos 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function japieventos_wp_title( $title, $sep ) {
	global $paged, $page;

	if ( is_feed() ) {
		return $title;
	}

	// Add the site name.
	$title .= get_bloginfo( 'name' );

	// Add the site description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) ) {
		$title = "$title $sep $site_description";
	}

	// Add a page number if necessary.
	if ( $paged >= 2 || $page >= 2 ) {
		$title = "$title $sep " . sprintf( __( 'Page %s', 'japieventos' ), max( $paged, $page ) );
	}

	return $title;
}
add_filter( 'wp_title', 'japieventos_wp_title', 10, 2 );

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * To override this in a child theme, remove the filter and optionally add
 * your own function tied to the wp_page_menu_args filter hook.
 *
 * @since Twenty Ten 1.0
 */
function japieventos_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'japieventos_page_menu_args' );

/**
 * Sets the post excerpt length to 40 characters.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 *
 * @since japieventos 1.0
 * @return int
 */
function japieventos_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'japieventos_excerpt_length' );

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and japieventos_continue_reading_link().
 *
 * @since japieventos 1.0
 */
function japieventos_auto_excerpt_more( $more ) {
	return ' &hellip;' . japieventos_continue_reading_link();
}
add_filter( 'excerpt_more', 'japieventos_auto_excerpt_more' );

/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @since japieventos 1.0
 * @return string "Continue Reading" link
 */
function japieventos_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'japieventos' ) . '</a>';
}

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @since japieventos 1.0
 * @return string Excerpt with a pretty "Continue Reading" link
 */
function japieventos_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= japieventos_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'japieventos_custom_excerpt_more' );

/**
 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
 *
 * To override japieventos_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 *
 * @since japieventos 1.0
 * @uses register_sidebar
 */
function japieventos_widgets_init() {
	// Area 1, located at the top of the sidebar.
	register_sidebar( array(
		'name' => __( 'Widget Lateral Principal', 'japieventos' ),
		'id' => 'primary-widget-area',
		'description' => __( 'Widget para agregar contenidos', 'japieventos' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h2 class="widget-title">',
		'after_title' => '<span class="circle"></span></h2>',
	) );
	
	// Footer 1
	register_sidebar( array(
		'name' => __( 'Footer Primero', 'japieventos' ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'Widget para agregar contenidos footer', 'japieventos' ),
		'before_widget' => '<aside id="%1$s" class="widget-container %2$s"><div class="inner">',
		'after_widget' => '</div></aside>',
		'before_title' => '<h4 class="widget-title">',
		'after_title' => '</h4>',
	) );
	
	// Footer 2
	register_sidebar( array(
		'name' => __( 'Footer Segundo', 'japieventos' ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'Widget para agregar contenidos footer', 'japieventos' ),
		'before_widget' => '<aside id="%1$s" class="widget-container %2$s"><div class="inner">',
		'after_widget' => '</div></aside>',
		'before_title' => '<h4 class="widget-title">',
		'after_title' => '</h4>',
	) );
	
	// Footer 2
	register_sidebar( array(
		'name' => __( 'Footer Tercero', 'japieventos' ),
		'id' => 'third-footer-widget-area',
		'description' => __( 'Widget para agregar contenidos footer', 'japieventos' ),
		'before_widget' => '<aside id="%1$s" class="widget-container %2$s"><div class="inner">',
		'after_widget' => '</div></aside>',
		'before_title' => '<h4 class="widget-title">',
		'after_title' => '</h4>',
	) );
	
}
/** Register sidebars by running japieventos_widgets_init() on the widgets_init hook. */
add_action( 'widgets_init', 'japieventos_widgets_init' );

/**
 * Register Lato Google font for japieventos.
 *
 * @since japieventos 1.0
 *
 * @return string
 */
function japieventos_font_url() {
	$font_url = '';
	/*
	 * Translators: If there are characters in your language that are not supported
	 * by the Font, translate this to 'off'. Do not translate into your own language.
	 */
	if ( 'off' !== _x( 'on', 'Font font: on or off', 'japieventos' ) ) {
		$font_url = add_query_arg( 'family', 'Arvo:400,700|Oxygen:400,700', "//fonts.googleapis.com/css" );

	}
	//Lato:300,400,700,300italic,400italic,700italic

	return $font_url;
}

/**
 * Enqueue scripts and styles for the front end.
 *
 * @since japieventos 1.0
 *
 * @return void
 */
function japieventos_scripts() {
	// Add Lato font, used in the main stylesheet.
	wp_enqueue_style( 'japieventos-font', japieventos_font_url(), array(), null );

	// Add Genericons font, used in the main stylesheet.
	wp_enqueue_style( 'genericons', get_template_directory_uri() . '/css/genericons.css', array(), '3.0.2' );

	// Load our main stylesheet.
	wp_enqueue_style( 'japieventos-style', get_stylesheet_uri(), array( 'genericons' ) );
	
	// Load the Internet Explorer specific stylesheet.
	wp_enqueue_style( 'japieventos-ie', get_template_directory_uri() . '/css/ie.css', array( 'japieventos-style', 'genericons' ), '20131205' );
	
	wp_style_add_data( 'japieventos-ie', 'conditional', 'lt IE 9' );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}

	
}
add_action( 'wp_enqueue_scripts', 'japieventos_scripts' );

/**
 * Enqueue Google fonts style to admin screen for custom header display.
 *
 * @since japieventos 1.0
 *
 * @return void
 */
function japieventos_admin_fonts() {
	wp_enqueue_style( 'japieventos-font', japieventos_font_url(), array(), null );
}
add_action( 'admin_print_scripts-appearance_page_custom-header', 'japieventos_admin_fonts' );


if ( ! function_exists( 'japieventos_posted_on' ) ) :
/**
 * Print HTML with meta information for the current post-date/time and author.
 *
 * @since japieventos  1.0
 *
 * @return void
 */
function japieventos_posted_on() {
	if ( is_sticky() && is_home() && ! is_paged() ) {
		echo '<span class="featured-post">' . __( 'Sticky', 'japieventos' ) . '</span>';
	}

	// Set up and print post meta information.
	printf( '<span class="entry-date"><a href="%1$s" rel="bookmark"><time class="entry-date" datetime="%2$s">%3$s</time></a></span> <span class="byline"><span class="author vcard"><a class="url fn n" href="%4$s" rel="author">%5$s</a></span></span>',
		esc_url( get_permalink() ),
		esc_attr( get_the_date( 'c' ) ),
		esc_html( get_the_date() ),
		esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
		get_the_author()
	);
}
endif;
if ( ! function_exists( 'japieventos_posted_in' ) ) :
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 *
 * @since japieventos 1.0
 */
function japieventos_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s.', 'japieventos' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'This entry was posted in %1$s.', 'japieventos' );
	} else {
		$posted_in = __( 'Bookmark the <a href="/%3$s/" rel="bookmark">permalink</a>.', 'japieventos' );
	}

	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}
endif;


/**
 * Add Admin
 *
 * @since japieventos 1.0
 */
	require_once(TEMPLATEPATH . '/theme-admin/general-options.php');

	// remove version info from head and feeds (http://digwp.com/2009/07/remove-wordpress-version-number/)
	function japieventos_complete_version_removal() {
		return '';
	}
	add_filter('the_generator', 'japieventos_complete_version_removal');

/**
 * Change Search Form input type from "text" to "search" and add placeholder text
 *
 * @since japieventos 1.0
 */
	function japieventos_search_form ( $form ) {
		$form = '<form role="search" method="get" id="searchform" action="' . home_url( '/' ) . '" >
		<div><label class="screen-reader-text" for="s">' . __('Search for:', 'japieventos') . '</label>
		<input type="search" placeholder="'. __('Search for:', 'japieventos'). '" value="' . get_search_query() . '" name="s" id="s" />
		<input type="submit" class="hide" id="searchsubmit" value="'. esc_attr__('Search') .'" />
		</div>
		</form>';
		return $form;
	}
	add_filter( 'get_search_form', 'japieventos_search_form' );


/**
 *  Adds excerpt on pages
 */
 
add_post_type_support( 'page', 'excerpt');

/**
 * Find out if blog has more than one category.
 *
 * @since japieventos 1.0
 *
 * @return boolean true if blog has more than 1 category
 */
function japieventos_categorized_blog() {
	if ( false === ( $all_the_cool_cats = get_transient( 'japieventos_category_count' ) ) ) {
		// Create an array of all the categories that are attached to posts
		$all_the_cool_cats = get_categories( array(
			'hide_empty' => 1,
		) );

		// Count the number of categories that are attached to the posts
		$all_the_cool_cats = count( $all_the_cool_cats );

		set_transient( 'japieventos_category_count', $all_the_cool_cats );
	}

	if ( 1 !== (int) $all_the_cool_cats ) {
		// This blog has more than 1 category so japieventos_categorized_blog should return true
		return true;
	} else {
		// This blog has only 1 category so japieventos_categorized_blog should return false
		return false;
	}
}

if ( ! function_exists( 'japieventos_paging_nav' ) ) :
/**
 * Display navigation to next/previous set of posts when applicable.
 *
 * @since japieventos 1.0
 *
 * @return void
 */
function japieventos_paging_nav() {
	// Don't print empty markup if there's only one page.
	if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
		return;
	}

	$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
	$pagenum_link = html_entity_decode( get_pagenum_link() );
	$query_args   = array();
	$url_parts    = explode( '?', $pagenum_link );

	if ( isset( $url_parts[1] ) ) {
		wp_parse_str( $url_parts[1], $query_args );
	}

	$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
	$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

	$format  = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
	$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

	// Set up paginated links.
	$links = paginate_links( array(
		'base'     => $pagenum_link,
		'format'   => $format,
		'total'    => $GLOBALS['wp_query']->max_num_pages,
		'current'  => $paged,
		'mid_size' => 1,
		'add_args' => array_map( 'urlencode', $query_args ),
		'prev_text' => __( '&larr; Previous', 'japieventos' ),
		'next_text' => __( 'Next &rarr;', 'japieventos' ),
	) );

	if ( $links ) :

	?>
	<nav class="navigation paging-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Posts navigation', 'japieventos' ); ?></h1>
		<div class="pagination loop-pagination">
			<?php echo $links; ?>
		</div><!-- .pagination -->
	</nav><!-- .navigation -->
	<?php
	endif;
}
endif;

if ( ! function_exists( 'japieventos_post_nav' ) ) :
/**
 * Display navigation to next/previous post when applicable.
 *
 * @since japieventos 1.0
 *
 * @return void
 */
function japieventos_post_nav() {
	// Don't print empty markup if there's nowhere to navigate.
	$previous = ( is_attachment() ) ? get_post( get_post()->post_parent ) : get_adjacent_post( false, '', true );
	$next     = get_adjacent_post( false, '', false );

	if ( ! $next && ! $previous ) {
		return;
	}

	?>
	<nav class="navigation post-navigation" role="navigation">
		<h1 class="screen-reader-text"><?php _e( 'Post navigation', 'japieventos' ); ?></h1>
		<div class="nav-links">
			<?php
			if ( is_attachment() ) :
				previous_post_link( '%link', __( '<span class="meta-nav">Published In</span>%title', 'japieventos' ) );
			else :
				previous_post_link( '%link', __( '<span class="meta-nav">Previous Post</span>%title', 'japieventos' ) );
				next_post_link( '%link', __( '<span class="meta-nav">Next Post</span>%title', 'japieventos' ) );
			endif;
			?>
		</div><!-- .nav-links -->
	</nav><!-- .navigation -->
	<?php
}
endif;

/**
 * Display an optional post thumbnail.
 *
 * Wraps the post thumbnail in an anchor element on index
 * views, or a div element when on single views.
 *
 * @since japieventos 1.0
 *
 * @return void
*/
function japieventos_post_thumbnail() {
	if ( post_password_required() || ! has_post_thumbnail() ) {
		return;
	}

	?>

	<div class="post-thumbnail">
	<?php the_post_thumbnail(); ?>
	</div>


	<?php 
}

/**
 * Comments Page Off
 *
 * @since japieventos 1.0
 *
*/
function my_default_content( $post_content, $post ) {
    if( $post->post_type )
    switch( $post->post_type ) {
        case 'page':
            $post->comment_status = 'closed';
        break;
    }
    return $post_content;
}
add_filter( 'default_content', 'my_default_content', 10, 2 );


// allow SVG uploads
add_filter('upload_mimes', 'custom_upload_mimes');
function custom_upload_mimes ( $existing_mimes=array() ) {
	$existing_mimes['svg'] = 'mime/type';
	return $existing_mimes;
}

// admin options
function edit_admin_menus() {
	global $menu;

	remove_menu_page('edit.php'); // Remove the Tools Menu
	remove_menu_page('edit-comments.php'); // Remove the Tools Menu
}

add_action( 'admin_menu', 'edit_admin_menus' );


class description_walker extends Walker_Nav_Menu {

  	function start_el(&$output, $item, $depth, $args) {


	  	global $wp_query;
       $indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
       $class_names = $value = '';
       $classes = empty( $item->classes ) ? array() : (array) $item->classes;

       $class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item ) );
       $class_names = ' class="'. esc_attr( $class_names ) . '"';

       $output .= $indent . '<li data-slide="'. $item->xfn  . '" id="menu-item-'. $item->ID . '"' . $value .'>';

       $attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
       $attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
       $attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
       $attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

      // $prepend = '<strong>';
      // $append = '</strong>';
       //$description  = ! empty( $item->description ) ? '<span>'.esc_attr( $item->description ).'</span>' : '';

       if($depth != 0)
       {
                 $description = $append = $prepend = "";
       }

        $item_output = $args->before;
        $item_output .= '<a'. $attributes .'>';
        $item_output .= $args->link_before .$prepend.apply_filters( 'the_title', $item->title, $item->ID ).$append;
        $item_output .= $description.$args->link_after;
        $item_output .= '</a>';
        $item_output .= $args->after;

        $output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
  	}
}

// soporte para fecha contact form en firefox y explorer
add_filter( 'wpcf7_support_html5_fallback', '__return_true' );

?>