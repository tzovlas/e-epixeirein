<?php
/**
 * Header free text (button) template.
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
$class = $class . ' header-hotline menu';
$subtitle = onestore_get_theme_mod( 'header_hotline_subtitle' );
$phone = trim( onestore_get_theme_mod( 'header_hotline_number' ) );
$icon = trim( onestore_get_theme_mod( 'header_hotline_icon' ) );
$button_class = onestore_get_theme_mod( 'header_hotline' );
$url = onestore_get_theme_mod( 'header_hotline_url' );
if ( ! $url ) {
	$url = 'tel:' . $phone;
}
?>
<div class="<?php echo esc_attr( $class ); ?>">
	<div class="button-inner">
	<a class="hotline-link d-flex" href='<?php echo esc_url( $url ); ?>'><?php

	if ( $icon ) {
		echo '<div class="hotline-icon d-flex d-v-center">';
		onestore_icon( $icon, array( 'class' => ' onestore-menu-icon x1_5' ) );
		echo '</div>';
	}
		echo '<div class="hotline-labels">';
	if ( $subtitle ) {
		echo '<span class="subtitle">' . esc_html( $subtitle ) . '</span>'; // WPCS: XSS ok.
	}
	if ( $phone ) {
		echo '<span class="phone-number text-b">' . esc_html( $phone ) . '</span>'; // WPCS: XSS ok.
	}

		echo '</div>';
	?></a>
	</div>
</div>
