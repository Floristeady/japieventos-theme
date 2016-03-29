<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the id=main div and all content
 * after.  Calls sidebar-footer.php for bottom widgets.
 *
 * @package WordPress
 * @subpackage japieventos
 * @since japieventos 1.0
 */
?>			
			
		</div><!-- #main -->
	
	</div><!-- #page -->
		
	<footer id="footer">
		<div class="row">
		<?php get_sidebar( 'footer' ); ?>
		</div>
	</footer>


	<?php wp_footer(); ?> 
	</body>
</html>