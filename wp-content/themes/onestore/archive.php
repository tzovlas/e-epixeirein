<?php
/**
 * The template for displaying archive pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package OneStore
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Header
 */
get_header();

/**
 * Primary - opening tag
 */
onestore_primary_open();

/**
 * Hook: onestore/frontend/before_main
 *
 * @hooked onestore_archive_header - 10
 */
do_action( 'onestore/frontend/before_main' );

if ( have_posts() ) :

	$columns = onestore_get_theme_mod( 'blog_index_columns' );
	$settings = onestore_string_to_devices( $columns );

	/**
	 * Hook: onestore/frontend/before_loop
	 */
	do_action( 'onestore/frontend/before_loop' );
	?>
	<div id="loop" <?php onestore_array_to_html_attributes( $settings ); ?> class="loop-posts device-columns">
		<?php
		// Start the loop.
		while ( have_posts() ) :
			the_post();

			// Render post content using selected layout on Customizer.
			onestore_get_template_part( 'post' );

		endwhile;
		?>
	</div>
	<?php

	/**
	 * Hook: onestore/frontend/after_loop
	 */
	do_action( 'onestore/frontend/after_loop' );

else :

	// Render no content notice.
	onestore_get_template_part( 'entry', 'none' );

endif;

/**
 * Hook: onestore/frontend/after_main
 *
 * @hooked onestore_loop_navigation - 10
 */
do_action( 'onestore/frontend/after_main' );

/**
 * Primary - closing tag
 */
onestore_primary_close();

/**
 * Sidebar
 */
get_sidebar();

/**
 * Footer
 */
get_footer();
