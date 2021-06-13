<?php
// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$class = $class . ' header-' . $slug . ' menu';
$user = wp_get_current_user();
$text = '';
$show_name = onestore_get_theme_mod( 'header_account_show_name' );
$size = onestore_get_theme_mod( 'header_account_img_size' );

if ( is_user_logged_in() && $user ) {
	$avatar = get_avatar_url( $user->ID );
	$text = $user->display_name;
	if ( ! $text ) {
		$text = $user->user_login;
	}
} else {
	$avatar = ONESTORE_IMAGES_URL . '/avatar.jpg';
	$text = esc_html__( 'Account', 'onestore' );
}

if ( ! $size ) {
	$size  = 'x1_5';
}


OneStore::instance()->add_hidden_canvas( 'account-content', 'canvas-account' );
?>
<div class="<?php echo esc_attr( 'header-' . $slug ); ?> my-account-item show-avatar menu action-toggle-menu">
	<div class="menu-item">
		<button data-target="off-canvas-account" class="my-account-toggle popup-toggle action-toggle" aria-expanded="false">
			<img class="user-avatar image-size <?php echo esc_attr( $size ); ?>" src="<?php echo esc_url( $avatar ); ?>" alt="">
			<?php if ( $show_name ) : ?>
			<span class="item-text"><?php echo $text; // WPCS: XSS ok. ?></span>
			<?php endif; ?>
		</button>
	</div>
</div>

