<?php
/**
 * The Sidebar containing the primary and secondary widget areas.
 *
 * @package WordPress
 * @subpackage japieventos
 * @since japieventos 1.0
 */
?>

<div id="sidebar" class="columns medium-3">
			
	<?php

		if ( is_active_sidebar( 'primary-widget-area' ) ) : ?>
		<div class="widget-list">
			<?php dynamic_sidebar( 'primary-widget-area' ); ?>
		</div>
		<?php endif; ?>

</div>