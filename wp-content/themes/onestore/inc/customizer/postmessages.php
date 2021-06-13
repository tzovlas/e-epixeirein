<?php
/**
 * Customizer & Front-End modification rules.
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
 * Global Settings > Color Palette
 * ====================================================
 */

for ( $i = 1; $i <= 8; $i++ ) {
	$add[ 'color_palette_' . $i ] = array(
		array(
			'type'     => 'css',
			'element'  => '.has-color-' . $i . '-background-color',
			'property' => 'background-color',
		),
		array(
			'type'     => 'css',
			'element'  => '.has-color-' . $i . '-color',
			'property' => 'color',
		),
	);
}


/**
 * ====================================================
 * General Styles > Button
 * ====================================================
 */

// foreach ( $media_queries as $suffix => $media ) {
// 	$add[ 'button_padding' . $suffix ] = array(
// 		array(
// 			'type'     => 'css',
// 			'element'  => 'body',
// 			'property' => '--button-padding',
// 			'media'    => $media,
// 		),
// 	);
// 	$add[ 'button_padding_sm' . $suffix ] = array(
// 		array(
// 			'type'     => 'css',
// 			'element'  => 'body',
// 			'property' => '--button-padding-sm',
// 			'media'    => $media,
// 		),
// 	);
// 	$add[ 'button_padding_lg' . $suffix ] = array(
// 		array(
// 			'type'     => 'css',
// 			'element'  => 'body',
// 			'property' => '--button-padding-lg',
// 			'media'    => $media,
// 		),
// 	);
// }


// $add['button_border'] = array(
// 	array(
// 		'type'     => 'css',
// 		'element'  => 'body',
// 		'property' => '--button-border-width',
// 	),
// );
// $add['button_border_radius'] = array(
// 	array(
// 		'type'     => 'css',
// 		'element'  => 'body',
// 		'property' => '--button-border-radius',
// 	),
// );


/**
 * ====================================================
 * Header > Element: Logo
 * ====================================================
 */

$add['header_logo_width'] = array(
	array(
		'type'     => 'css',
		'element'  => '.header-logo .onestore-logo-image',
		'property' => 'width',
	),
);
$add['header_mobile_logo_width'] = array(
	array(
		'type'     => 'css',
		'element'  => '.header-mobile-logo .onestore-logo-image',
		'property' => 'width',
	),
);

/**
 * ====================================================
 * Header > Element: Search
 * ====================================================
 */

$add['header_search_bar_width'] = array(
	array(
		'type'     => 'css',
		'element'  => '.header-search-bar .search-form',
		'property' => 'width',
	),
);
$add['header_search_dropdown_width'] = array(
	array(
		'type'     => 'css',
		'element'  => '.header-search-dropdown .sub-menu',
		'property' => 'width',
	),
);

/**
 * ====================================================
 * Header > Element: Account
 * ====================================================
 */

$add['header_account_avatar_size'] = array(
	array(
		'type'     => 'css',
		'element'  => '.my-account-item .user-avatar',
		'property' => 'width',
	),
	array(
		'type'     => 'css',
		'element'  => '.my-account-item .user-avatar',
		'property' => 'height',
	),
);


/**
 * ====================================================
 * Header > Element: Shopping Cart
 * ====================================================
 */

$add['header_cart_count_bg_color'] = array(
	array(
		'type'     => 'css',
		'element'  => '.header-shopping-cart .shopping-cart-count',
		'property' => 'background-color',
	),
);
$add['header_cart_count_text_color'] = array(
	array(
		'type'     => 'css',
		'element'  => '.header-shopping-cart .shopping-cart-count',
		'property' => 'color',
	),
);

/**
 * ====================================================
 * Header > Element: Social
 * ====================================================
 */

$add['header_social_links_target'] = array(
	array(
		'type'     => 'html',
		'element'  => '.header-social-links a',
		'property' => 'target',
		'pattern'  => '_$',
	),
);


/**
 * ====================================================
 * Footer > Bottom Bar
 * ====================================================
 */

$add['footer_bottom_bar_merged_gap'] = array(
	array(
		'type'     => 'css',
		'element'  => '.onestore-footer-bottom-bar.main-section-merged',
		'property' => 'margin-top',
	),
);

$add['footer_bottom_bar_container'] = array(
	array(
		'type'     => 'class',
		'element'  => '.onestore-footer-bottom-bar',
		'pattern'  => 'main-section-$',
	),
);


foreach ( $media_queries as $suffix => $media ) {
	$add[ 'footer_bottom_bar_padding' . $suffix ] = array(
		array(
			'type'     => 'css',
			'element'  => '.onestore-footer-bottom-bar-inner .onestore-footer-bottom-bar-row',
			'property' => 'padding',
			'media'    => $media,
		),
	);
}
$add['footer_bottom_bar_border'] = array(
	array(
		'type'     => 'css',
		'element'  => '.onestore-footer-bottom-bar-inner .onestore-footer-bottom-bar-row',
		'property' => 'border-width',
	),
);


foreach ( array( 'font_family', 'font_weight', 'font_style', 'text_transform', 'font_size', 'line_height', 'letter_spacing' ) as $prop ) {
	$element = '.onestore-footer-bottom-bar';
	$property = str_replace( '_', '-', $prop );

	$add[ 'footer_bottom_bar_' . $prop ] = array(
		array(
			'type'     => 'font_family' === $prop ? 'font' : 'css',
			'element'  => $element,
			'property' => $property,
		),
	);

	if ( in_array( $prop, array( 'font_size', 'line_height', 'letter_spacing' ) ) ) {
		$add[ 'footer_bottom_bar_' . $prop . '__tablet' ] = array(
			array(
				'type'     => 'css',
				'element'  => $element,
				'property' => $property,
				'media'    => '@media screen and (max-width: 1023px)',
			),
		);
		$add[ 'footer_bottom_bar_' . $prop . '__mobile' ] = array(
			array(
				'type'     => 'css',
				'element'  => $element,
				'property' => $property,
				'media'    => '@media screen and (max-width: 499px)',
			),
		);
	}
}

$add['footer_bottom_bar_bg_color'] = array(
	array(
		'type'     => 'css',
		'element'  => '.onestore-footer-bottom-bar-inner',
		'property' => 'background-color',
	),
);
$add['footer_bottom_bar_border_color'] = array(
	array(
		'type'     => 'css',
		'element'  => '.onestore-footer-bottom-bar-inner',
		'property' => 'border-color',
	),
);
$add['footer_bottom_bar_text_color'] = array(
	array(
		'type'     => 'css',
		'element'  => '.onestore-footer-bottom-bar',
		'property' => 'color',
	),
);
$add['footer_bottom_bar_link_text_color'] = array(
	array(
		'type'     => 'css',
		'element'  => '.onestore-footer-bottom-bar a:not(.button)',
		'property' => 'color',
	),
);
$add['footer_bottom_bar_link_hover_text_color'] = array(
	array(
		'type'     => 'css',
		'element'  => '.onestore-footer-bottom-bar a:not(.button):hover, .onestore-footer-bottom-bar a:not(.button):focus',
		'property' => 'color',
	),
);

/**
 * ====================================================
 * Footer > Social
 * ====================================================
 */

// Social links
$add['footer_social_links_target'] = array(
	array(
		'type'     => 'html',
		'element'  => '.onestore-footer-social-links a',
		'property' => 'target',
		'pattern'  => '_$',
	),
);

/**
 * ====================================================
 * Footer > Scroll To Top
 * ====================================================
 */

$add['scroll_to_top_display'] = array(
	array(
		'type'     => 'class',
		'element'  => '.onestore-scroll-to-top',
		'pattern'  => 'onestore-scroll-to-top-display-$',
	),
);
$add['scroll_to_top_position'] = array(
	array(
		'type'     => 'class',
		'element'  => '.onestore-scroll-to-top',
		'pattern'  => 'onestore-scroll-to-top-position-$',
	),
);

$responsive = array(
	''         => '',
	'__tablet' => '@media screen and (max-width: 1023px)',
	'__mobile' => '@media screen and (max-width: 499px)',
);
foreach ( $responsive as $suffix => $media ) {
	$add[ 'scroll_to_top_h_offset' . $suffix ] = array(
		array(
			'type'     => 'css',
			'element'  => '.onestore-scroll-to-top',
			'property' => 'margin-left',
			'media'    => $media,
		),
		array(
			'type'     => 'css',
			'element'  => '.onestore-scroll-to-top',
			'property' => 'margin-right',
			'media'    => $media,
		),
	);

	$add[ 'scroll_to_top_v_offset' . $suffix ] = array(
		array(
			'type'     => 'css',
			'element'  => '.onestore-scroll-to-top',
			'property' => 'margin-bottom',
			'media'    => $media,
		),
	);

	$add[ 'scroll_to_top_icon_size' . $suffix ] = array(
		array(
			'type'     => 'css',
			'element'  => '.onestore-scroll-to-top',
			'property' => 'font-size',
			'media'    => $media,
		),
	);

	$add[ 'scroll_to_top_padding' . $suffix ] = array(
		array(
			'type'     => 'css',
			'element'  => '.onestore-scroll-to-top',
			'property' => 'padding',
			'media'    => $media,
		),
	);

	$add[ 'scroll_to_top_border_radius' . $suffix ] = array(
		array(
			'type'     => 'css',
			'element'  => '.onestore-scroll-to-top',
			'property' => 'border-radius',
			'media'    => $media,
		),
	);
}

$add['scroll_to_top_bg_color'] = array(
	array(
		'type'     => 'css',
		'element'  => '.onestore-scroll-to-top',
		'property' => 'background-color',
	),
	array(
		'type'     => 'css',
		'element'  => '.onestore-scroll-to-top:hover, .onestore-scroll-to-top:focus',
		'property' => 'background-color',
	),
);
$add['scroll_to_top_text_color'] = array(
	array(
		'type'     => 'css',
		'element'  => '.onestore-scroll-to-top',
		'property' => 'color',
	),
	array(
		'type'     => 'css',
		'element'  => '.onestore-scroll-to-top:hover, .onestore-scroll-to-top:focus',
		'property' => 'color',
	),
);
$add['scroll_to_top_hover_bg_color'] = array(
	array(
		'type'     => 'css',
		'element'  => '.onestore-scroll-to-top:hover, .onestore-scroll-to-top:focus',
		'property' => 'background-color',
	),
);
$add['scroll_to_top_hover_text_color'] = array(
	array(
		'type'     => 'css',
		'element'  => '.onestore-scroll-to-top:hover, .onestore-scroll-to-top:focus',
		'property' => 'color',
	),
);

/**
 * ====================================================
 * Blog > Post Layout: Default
 * ====================================================
 */

$add['blog_index_default_items_gap'] = array(
	array(
		'type'     => 'css',
		'element'  => '.onestore-loop-default .entry',
		'property' => 'margin-bottom',
	),
);

$add['entry_header_alignment'] = array(
	array(
		'type'     => 'class',
		'element'  => '.entry-layout-default .entry-header',
		'pattern'  => 'text-$',
	),
);

$add['entry_footer_alignment'] = array(
	array(
		'type'     => 'class',
		'element'  => '.entry-layout-default .entry-footer',
		'pattern'  => 'text-$',
	),
);

/**
 * ====================================================
 * Blog > Posts
 * ====================================================
 */

foreach ( $media_queries as $suffix => $media ) {
	$add[ 'blog_index_thumb_size' . $suffix ] = array(
		array(
			'type'     => 'css',
			'element'  => '.loop-posts .item-builder--thumb',
			'property' => 'padding-top',
			'pattern'  => '$',
			'media'    => $media,
		),
	);
	$add[ 'blog_index_cols_gutter' . $suffix ] = array(
		array(
			'type'     => 'css',
			'element'  => '.loop-posts.device-columns .col',
			'property' => 'padding-left',
			'pattern'  => '$',
			'media'    => $media,
		),
		array(
			'type'     => 'css',
			'element'  => '.loop-posts.device-columns .col',
			'property' => 'padding-right',
			'pattern'  => '$',
			'media'    => $media,
		),

		array(
			'type'     => 'css',
			'element'  => '.loop-posts.device-columns',
			'property' => 'margin-right',
			'pattern'  => '-$',
			'media'    => $media,
		),
		array(
			'type'     => 'css',
			'element'  => '.loop-posts.device-columns',
			'property' => 'margin-left',
			'pattern'  => '-$',
			'media'    => $media,
		),

	);
	$add[ 'blog_index_rows_gutter' . $suffix ] = array(
		array(
			'type'     => 'css',
			'element'  => '.loop-posts .col',
			'property' => 'margin-bottom',
			'pattern'  => '$',
			'media'    => $media,
		),
	);

}


return $add;
