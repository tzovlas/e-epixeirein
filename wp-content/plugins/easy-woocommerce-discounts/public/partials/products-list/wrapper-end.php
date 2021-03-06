<?php
// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( function_exists( 'wc_get_default_products_per_row' ) ) {
	echo '</div>';
} else {
	$template = get_option( 'template' );

	switch ( $template ) {
		case 'twentyeleven' :
			echo '</div>';
			get_sidebar( 'shop' );
			echo '</div>';
			break;
		case 'twentytwelve' :
			echo '</div></div>';
			break;
		case 'twentythirteen' :
			echo '</div></div>';
			break;
		case 'twentyfourteen' :
			echo '</div></div></div>';
			get_sidebar( 'content' );
			break;
		case 'twentyfifteen' :
			echo '</div></div>';
			break;
		case 'twentysixteen' :
			echo '</main></div>';
			break;
		default :
			echo '</div></div>';
			break;
	}

	echo '</div>';
}
