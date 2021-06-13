<?php
OneStore_Customizer::set_wp_customize();
OneStore_Customizer::no_panel();

OneStore_Customizer::section_start( 'onestore_section_header_mobile_vertical_bar' );
OneStore_Customizer::set_current_priority( 20 );


OneStore_Customizer::add_field(
	'header_mobile_vertical_bar_display',
	array(
		'control' => array(
			'type' => 'select',
			'label'       => esc_html__( 'Display', 'onestore' ),
			'choices'     => array(
				'drawer'      => esc_html__( 'Drawer (slide in popup)', 'onestore' ),
				'full-screen' => esc_html__( 'Full screen', 'onestore' ),
			),
		),
	)
);

OneStore_Customizer::add_field(
	'header_mobile_vertical_bar_position',
	array(
		'control' => array(
			'type' => 'select',
			'label'       => esc_html__( 'Position', 'onestore' ),
			'choices'     => array(
				'left'   => is_rtl() ? esc_html__( 'Right', 'onestore' ) : esc_html__( 'Left', 'onestore' ),
				'right'  => is_rtl() ? esc_html__( 'Left', 'onestore' ) : esc_html__( 'Right', 'onestore' ),
				'center' => esc_html__( 'Center (only for Full Screen)', 'onestore' ),
			),
		),
		'css_live' => array(
			array(
				'type'     => 'class',
				'element'  => '.header-mobile-vertical',
				'pattern'  => 'popup-position-$',
			),
		),
	)
);


OneStore_Customizer::add_field(
	'header_mobile_vertical_bar_items_gutter',
	array(
		'control_class' => 'slider',
		'control' => array(
			'label'       => esc_html__( 'Spacing between elements', 'onestore' ),
			'units'       => array(
				'px' => array(
					'min'   => 0,
					'max'   => 40,
					'step'  => 1,
				),
			),
		),
		'live_css' => array(
			array(
				'type'     => 'css',
				'element'  => '.header-mobile-vertical-bar .header-section-vertical-row > *',
				'property' => 'padding',
				'pattern'  => '$ 0',
			),
			array(
				'type'     => 'css',
				'element'  => '.header-mobile-vertical-bar .header-section-vertical-column',
				'property' => 'margin',
				'pattern'  => '-$ 0',
			),
		),
	)
);
OneStore_Customizer::add_field(
	'header_mobile_vertical_bar_icon_size',
	array(
		'control_class' => 'slider',
		'control' => array(
			'label'       => esc_html__( 'Icon size', 'onestore' ),
			'units'       => array(
				'px' => array(
					'min'  => 0,
					'max'  => 60,
					'step' => 1,
				),
			),
		),
		'live_css' => array(
			array(
				'type'     => 'css',
				'element'  => '.header-mobile-vertical-bar .onestore-menu-icon',
				'property' => 'font-size',
			),
		),
	)
);



OneStore_Customizer::section_end();
