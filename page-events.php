<?php
/**
* Template Name: P&aacute;gina Eventos
 * @package WordPress
 * @subpackage japieventos
 * @since japieventos 1.0
 */
?>
<?php 

if($post->post_parent) {
	header ('HTTP/1.1 301 Moved Permanently'); 
	header ('Location: '.get_permalink($post->post_parent)); 
} else {
	wp_redirect( home_url() ); exit;
}
?>