<?php
/**
 * Customizer settings: Header > Cart
 *
 * @package OneStore
 **/

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = 'onestore_section_header_account';

/**
 * ====================================================
 * Colors
 * ====================================================
 */

if ( ! class_exists( 'WooCommerce' ) ) {
	// Notice.
	$wp_customize->add_control(
		new OneStore_Customize_Control_Blank(
			$wp_customize,
			'notice_header_account',
			array(
				'section'     => $section,
				'settings'    => array(),
				'description' => '<div class="notice notice-warning notice-alt inline"><p>' . esc_html__( 'Only available if WooCommerce plugin is installed and activated.', 'onestore' ) . '</p></div>',
				'priority'    => 10,
			)
		)
	);
}

// Avatar width.
$key = 'header_account_show_name';
$wp_customize->add_setting(
	$key,
	array(
		'default'     => onestore_array_value( $defaults, $key ),
		'sanitize_callback' => array( 'OneStore_Customizer_Sanitization', 'toggle' ),
	)
);
$wp_customize->add_control(
	new OneStore_Customize_Control_Toggle(
		$wp_customize,
		$key,
		array(
			'section'     => $section,
			'label'       => esc_html__( 'Show name', 'onestore' ),
			'priority'    => 10,
		)
	)
);

$key = 'header_account_img_size';
$wp_customize->add_setting(
	$key,
	array(
		'default'     => onestore_array_value( $defaults, $key ),
		'sanitize_callback' => array( 'OneStore_Customizer_Sanitization', 'select' ),
	)
);
$wp_customize->add_control(
	$key,
	array(
		'section'     => $section,
		'label'       => esc_html__( 'Avatar size', 'onestore' ),
		'type' => 'select',
		'choices' => array(
			'default' => esc_html__( 'Default', 'onestore' ),
			'x1_5' => esc_html__( 'x1.5', 'onestore' ),
			'x2' => esc_html__( 'x2', 'onestore' ),
			'x1' => esc_html__( 'x1', 'onestore' ),
		),
		'priority'    => 10,
	)
);

