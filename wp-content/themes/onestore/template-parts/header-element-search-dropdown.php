<?php
/**
 * Header search dropdown template.
 *
 * Passed variables:
 *
 * @type string $slug Header element slug.
 *
 * @package OneStore
 */

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


OneStore::instance()->add_hidden_canvas( 'canvas-search', 'canvas-search' );
?>
<div class="<?php echo esc_attr( 'header-' . $slug ); ?> header-search menu action-toggle-menu">
	<div class="menu-item">
	<button data-target="off-canvas-search" class="canvas-search-toggle popup-toggle action-toggle" aria-expanded="false">
			<?php onestore_icon( 'search', array( 'class' => 'onestore-menu-icon' ) ); ?>
			<span class="screen-reader-text"><?php esc_html_e( 'Search', 'onestore' ); ?></span>
		</button>
	</div>
</div>
