<?php
/**
 * Customizer & Front-End modification rules for WooCommerce.
 *
 * @package OneStore
 **/

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$add = array();

$media_queries = OneStore::instance()->get_media_queries();

/**
 * ====================================================
 * General Styles > Border & Subtitle Background
 * ====================================================
 */

$add['subtitle_color'] = array(
	array(
		'type'     => 'css',
		'element'  => '.woocommerce .woocommerce-error, .woocommerce .woocommerce-info, .woocommerce .woocommerce-message, .woocommerce table.shop_attributes tr:nth-child(even) th, .woocommerce table.shop_attributes tr:nth-child(even) td, #add_payment_method #payment ul.payment_methods li, .woocommerce-cart #payment ul.payment_methods li, .woocommerce-checkout #payment ul.payment_methods li, .woocommerce nav.woocommerce-pagination ul li span.current, .woocommerce ul.order_details li, .woocommerce-account ol.commentlist.notes li.note',
		'property' => 'background-color',
	),
);




/**
 * ====================================================
 * WooCommerce > Store Notice
 * ====================================================
 */

$add['woocommerce_demo_store_notice_bg_color'] = array(
	array(
		'type'     => 'css',
		'element'  => '.woocommerce-store-notice, p.demo_store',
		'property' => 'background-color',
	),
);
$add['woocommerce_demo_store_notice_text_color'] = array(
	array(
		'type'     => 'css',
		'element'  => '.woocommerce-store-notice, p.demo_store, .woocommerce-store-notice a, p.demo_store a',
		'property' => 'color',
	),
);

/**
 * ====================================================
 * WooCommerce > Products Grid
 * ====================================================
 */


foreach ( $media_queries as $suffix => $media ) {

	$add[ 'woocommerce_products_grid_rows_gutter' . $suffix ] = array(
		array(
			'type'     => 'css',
			'element'  => '.woocommerce ul.products',
			'property' => 'margin-top',
			'pattern'  => '-$',
			'media'    => $media,
		),
		array(
			'type'     => 'css',
			'element'  => '.woocommerce ul.products',
			'property' => 'margin-bottom',
			'pattern'  => '-$',
			'media'    => $media,
		),
		array(
			'type'     => 'css',
			'element'  => '.woocommerce ul.products li.product',
			'property' => 'padding-top',
			'media'    => $media,
		),
		array(
			'type'     => 'css',
			'element'  => '.woocommerce ul.products li.product',
			'property' => 'padding-bottom',
			'media'    => $media,
		),
	);

	$add[ 'woocommerce_products_grid_columns_gutter' . $suffix ] = array(
		array(
			'type'     => 'css',
			'element'  => '.woocommerce ul.products',
			'property' => 'margin-left',
			'pattern'  => '-$',
			'media'    => $media,
		),
		array(
			'type'     => 'css',
			'element'  => '.woocommerce ul.products',
			'property' => 'margin-right',
			'pattern'  => '-$',
			'media'    => $media,
		),
		array(
			'type'     => 'css',
			'element'  => '.woocommerce ul.products li.product',
			'property' => 'padding-left',
			'media'    => $media,
		),
		array(
			'type'     => 'css',
			'element'  => '.woocommerce ul.products li.product',
			'property' => 'padding-right',
			'media'    => $media,
		),
	);

	$add[ 'woocommerce_index_thumb_height' . $suffix ] = array(
		array(
			'type'     => 'css',
			'element'  => '.product .item-builder--thumb',
			'property' => 'padding-top',
			'media'    => $media,
		),
	);

}


/**
 * ====================================================
 * WooCommerce > Single Product Page
 * ====================================================
 */


$add['woocommerce_cart_width'] = array(
	array(
		'type'     => 'css',
		'element'  => '.woocommerce-cart #content .wrapper, .woocommerce-cart #content .section-contained>.section-inner ',
		'property' => 'max-width',
	),
);

$add['woocommerce_checkout_width'] = array(
	array(
		'type'     => 'css',
		'element'  => '.woocommerce-checkout #content .wrapper, .woocommerce-checkout #content .section-contained>.section-inner ',
		'property' => 'max-width',
	),
);



/**
 * ====================================================
 * WooCommerce > Other Elements
 * ====================================================
 */

$add['woocommerce_sale_badge_bg_color'] = array(
	array(
		'type'     => 'css',
		'element'  => '.woocommerce span.onsale',
		'property' => 'background-color',
	),
);
$add['woocommerce_sale_badge_text_color'] = array(
	array(
		'type'     => 'css',
		'element'  => '.woocommerce span.onsale',
		'property' => 'color',
	),
);

$add['woocommerce_sale_badge_border_color'] = array(
	array(
		'type'     => 'css',
		'element'  => '.woocommerce span.onsale',
		'property' => 'border-color',
	),
);

$add['woocommerce_review_star_color'] = array(
	array(
		'type'     => 'css',
		'element'  => '.woocommerce .star-rating, .woocommerce p.stars a',
		'property' => 'color',
	),
);

foreach ( array(
	'bg' => 'background-color',
	'border' => 'border-color',
	'text' => 'color',
) as $key => $prop ) {
	$add[ 'woocommerce_alt_button_' . $key . '_color' ] = array(
		array(
			'type'     => 'css',
			'element'  => '.woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt',
			'property' => $prop,
		),
		array(
			'type'     => 'css',
			'element'  => '.woocommerce #respond input#submit.alt.disabled, .woocommerce #respond input#submit.alt:disabled, .woocommerce #respond input#submit.alt:disabled[disabled], .woocommerce a.button.alt.disabled, .woocommerce a.button.alt:disabled, .woocommerce a.button.alt:disabled[disabled], .woocommerce button.button.alt.disabled, .woocommerce button.button.alt:disabled, .woocommerce button.button.alt:disabled[disabled], .woocommerce input.button.alt.disabled, .woocommerce input.button.alt:disabled, .woocommerce input.button.alt:disabled[disabled]',
			'property' => $prop,
		),
	);
}
foreach ( array(
	'bg' => 'background-color',
	'border' => 'border-color',
	'text' => 'color',
) as $key => $prop ) {
	$add[ 'woocommerce_alt_button_hover_' . $key . '_color' ] = array(
		array(
			'type'     => 'css',
			'element'  => '.woocommerce #respond input#submit.alt:hover, .woocommerce #respond input#submit.alt:focus, .woocommerce a.button.alt:hover, woocommerce a.button.alt:focus, .woocommerce button.button.alt:hover, .woocommerce button.button.alt:focus, .woocommerce input.button.alt:hover, .woocommerce input.button.alt:focus',
			'property' => $prop,
		),
		array(
			'type'     => 'css',
			'element'  => '.woocommerce #respond input#submit.alt.disabled:hover, .woocommerce #respond input#submit.alt.disabled:focus, .woocommerce #respond input#submit.alt:disabled:hover, .woocommerce #respond input#submit.alt:disabled:focus, .woocommerce #respond input#submit.alt:disabled[disabled]:hover, .woocommerce #respond input#submit.alt:disabled[disabled]:focus, .woocommerce a.button.alt.disabled:hover, .woocommerce a.button.alt.disabled:focus, .woocommerce a.button.alt:disabled:hover, .woocommerce a.button.alt:disabled:focus, .woocommerce a.button.alt:disabled[disabled]:hover, .woocommerce a.button.alt:disabled[disabled]:focus, .woocommerce button.button.alt.disabled:hover, .woocommerce button.button.alt.disabled:focus, .woocommerce button.button.alt:disabled:hover, .woocommerce button.button.alt:disabled:focus, .woocommerce button.button.alt:disabled[disabled]:hover, .woocommerce button.button.alt:disabled[disabled]:focus, .woocommerce input.button.alt.disabled:hover, .woocommerce input.button.alt.disabled:focus, .woocommerce input.button.alt:disabled:hover, .woocommerce input.button.alt:disabled:focus, .woocommerce input.button.alt:disabled[disabled]:hover, .woocommerce input.button.alt:disabled[disabled]:focus',
			'property' => $prop,
		),
	);
}

return $add;
