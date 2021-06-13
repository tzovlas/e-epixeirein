<?php
$ajax = onestore_get_theme_mod( 'heading_header_search_ajax' );
$product_only = false;
$product_cat = false;
if ( function_exists( 'WC' ) ) {
	$product_only = onestore_get_theme_mod( 'heading_header_search_product_only' );
	$product_cat  = onestore_get_theme_mod( 'heading_header_search_product_cat' );
}

$form_classes = 'search-form';
if ( $ajax ) {
	$form_classes .= ' ajax-form';
}

if ( $product_cat ) {
	$form_classes .= ' show-cat';
}

?>
<form role="search" method="get" class="<?php echo esc_attr( $form_classes ); ?>" action="<?php home_url( '/' ); ?>">
	<?php if ( function_exists( 'WC' ) ) : ?>
		<?php if ( $product_only ) : ?>
		<input type="hidden" name="post_type" value="product" />
		<?php endif ?>
		<?php
		if ( $product_cat ) {
			wc_product_dropdown_categories(
				array(
					'show_option_none' => __( 'All', 'onestore' ),
					'show_count' => false,
				)
			);
		}
		?>
	<?php endif; ?>
	<label>
		<input type="search" <?php if ( $ajax ) {
			?> autocomplete="off" <?php } ?> class="search-field" placeholder="<?php esc_attr_e( 'Search…', 'onestore' ); ?>" value="<?php the_search_query(); ?>"  name="s">
	</label>
	<button type="submit" class="search-submit">
		<?php onestore_icon( 'search', array( 'class' => 'onestore-search-icon' ) ); ?>
		<span class="submit-text"><?php esc_html_e( 'Search', 'onestore' ); ?></span>
	</button>
</form>
