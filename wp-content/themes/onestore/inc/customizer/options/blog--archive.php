<?php
/**
 * Customizer settings: Blog > Posts Index
 *
 * @package OneStore
 **/

// Prevent direct access.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$section = 'onestore_section_blog_index';

$key = 'blog_index_columns';
$wp_customize->add_setting(
	$key,
	array(
		'default'     => onestore_array_value( $defaults, $key ),
		'sanitize_callback' => false,
	)
);
$wp_customize->add_control(
	$key,
	array(
		'type'        => 'text',
		'section'     => $section,
		'label'       => sprintf( esc_html__( 'Layout Columns', 'onestore' ), $i ),
	)
);


$key = 'blog_index_thumb_size';
$settings = array(
	$key,
	$key . '__tablet',
	$key . '__mobile',
);
foreach ( $settings as $sub_key ) {
	$wp_customize->add_setting(
		$sub_key,
		array(
			'default'     => onestore_array_value( $defaults, $sub_key ),
			'sanitize_callback' => array( 'OneStore_Customizer_Sanitization', 'dimension' ),
			'transport'   => 'postMessage',
		)
	);
}

$wp_customize->add_control(
	new OneStore_Customize_Control_Slider(
		$wp_customize,
		$key,
		array(
			'section'     => $section,
			'settings'     => $settings,
			'label'       => esc_html__( 'Thumbnail Size', 'onestore' ),
			'units'       => array(
				'px' => array(
					'min'  => 0,
					'max'  => 800,
					'step' => 1,
					'label' => 'px',
				),
				'%' => array(
					'min'  => 0,
					'max'  => 450,
					'step' => 0.1,
					'label' => '%',
				),
				'em' => array(
					'min'  => 0,
					'max'  => 200,
					'step' => 0.1,
					'label' => 'em',
				),
			),
			'priority'    => 10,
		)
	)
);

$key = 'blog_index_cols_gutter';
$settings = array(
	$key,
	$key . '__tablet',
	$key . '__mobile',
);
foreach ( $settings as $sub_key ) {
	$wp_customize->add_setting(
		$sub_key,
		array(
			'default'     => onestore_array_value( $defaults, $sub_key ),
			'sanitize_callback' => array( 'OneStore_Customizer_Sanitization', 'dimension' ),
			'transport'   => 'postMessage',
		)
	);
}
$wp_customize->add_control(
	new OneStore_Customize_Control_Slider(
		$wp_customize,
		$key,
		array(
			'section'     => $section,
			'settings'     => $settings,
			'label'       => esc_html__( 'Columns Gutter', 'onestore' ),
			'units'       => array(
				'px' => array(
					'min'  => 0,
					'max'  => 200,
					'step' => 1,
					'label' => 'px',
				),
				'em' => array(
					'min'  => 0,
					'max'  => 200,
					'step' => 0.1,
					'label' => 'em',
				),
			),
			'priority'    => 11,
		)
	)
);

$key = 'blog_index_rows_gutter';
$settings = array(
	$key,
	$key . '__tablet',
	$key . '__mobile',
);
foreach ( $settings as $sub_key ) {
	$wp_customize->add_setting(
		$sub_key,
		array(
			'default'     => onestore_array_value( $defaults, $sub_key ),
			'sanitize_callback' => array( 'OneStore_Customizer_Sanitization', 'dimension' ),
			'transport'   => 'postMessage',
		)
	);
}
$wp_customize->add_control(
	new OneStore_Customize_Control_Slider(
		$wp_customize,
		$key,
		array(
			'section'     => $section,
			'settings'     => $settings,
			'label'       => esc_html__( 'Rows Gutter', 'onestore' ),
			'units'       => array(
				'px' => array(
					'min'  => 0,
					'max'  => 200,
					'step' => 1,
					'label' => 'px',
				),
				'em' => array(
					'min'  => 0,
					'max'  => 200,
					'step' => 0.1,
					'label' => 'em',
				),
			),
			'priority'    => 12,
		)
	)
);



// Navigation mode.
$key = 'blog_index_navigation_mode';
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
		'type'        => 'select',
		'section'     => $section,
		'label'       => esc_html__( 'Navigation mode', 'onestore' ),
		'choices'     => array(
			'prev-next'  => esc_html__( 'Prev / Next buttons', 'onestore' ),
			'pagination' => esc_html__( 'Pagination (page numbers)', 'onestore' ),
		),
		'priority'    => 15,
	)
);

// ------
$wp_customize->add_control(
	new OneStore_Customize_Control_HR(
		$wp_customize,
		'hr_blog_index_navigation',
		array(
			'section'     => $section,
			'settings'    => array(),
			'priority'    => 20,
		)
	)
);

// Elements to display.
$key = 'post_index_meta_elements';
$wp_customize->add_setting(
	$key,
	array(
		'default'     => onestore_array_value( $defaults, $key ),
		'sanitize_callback' => array( 'OneStore_Customizer_Sanitization', 'multiselect' ),
	)
);
$wp_customize->add_control(
	new OneStore_Customize_Control_Builder(
		$wp_customize,
		$key,
		array(
			'section'     => $section,
			'label'       => esc_html__( 'Meta elements to display', 'onestore' ),
			'choices'     => array(
				'author' => esc_html__( 'Author', 'onestore' ),
				'date' => esc_html__( 'Date', 'onestore' ),
				'categories' => esc_html__( 'Categories', 'onestore' ),
				'tags' => esc_html__( 'Tags', 'onestore' ),
				'comments' => esc_html__( 'Comments', 'onestore' ),
			),
			'layout'      => 'block',
			'priority'    => 40,
		)
	)
);


// Entry grid excerpt length.
$key = 'post_index_excerpt_length';
$wp_customize->add_setting(
	$key,
	array(
		'default'     => onestore_array_value( $defaults, $key ),
		'sanitize_callback' => array( 'OneStore_Customizer_Sanitization', 'dimension' ),
	)
);
$wp_customize->add_control(
	new OneStore_Customize_Control_Slider(
		$wp_customize,
		$key,
		array(
			'section'     => $section,
			'label'       => esc_html__( 'Excerpt words limit', 'onestore' ),
			'description' => esc_html__( 'Fill with 0 to disable excerpt.', 'onestore' ),
			'units'       => array(
				'' => array(
					'min'  => 0,
					'max'  => 200,
					'step' => 1,
					'label' => 'wrd',
				),
			),
			'priority'    => 50,
		)
	)
);
