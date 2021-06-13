<?php
class OneStore_Widget_Tab_Settings {
	public function __construct() {
		add_action( 'admin_footer', array( $this, 'product_tabs_settings' ) );
		add_action( 'wp_ajax_onestore_widget_tpl', array( $this, 'template' ) );

	}

	public function template() {
		global $wpdb;
		$nonce = sanitize_text_field( $_REQUEST['_nonce'] );
		if ( ! wp_verify_nonce( $nonce, 'onestore' ) ) {
			die( 'Security check' );
		}

		$this->product_tabs_settings();
		wp_die();
	}

	public function product_tabs_settings() {
	}

	public function post_tabs_settings() {

	}
}

new OneStore_Widget_Tab_Settings();
