<?php

OneStore_Customizer::set_wp_customize();
OneStore_Customizer::no_panel();

OneStore_Customizer::section_start( 'onestore_section_footer_bottom_bar' );
OneStore_Customizer::set_current_priority( 20 );


OneStore_Customizer::add_field(
	'footer_bottom_bar_items_gutter',
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
				'element'  => '.onestore-footer-bottom-bar .onestore-footer-column > *',
				'property' => 'padding',
				'pattern'  => '0 $',
			),
			array(
				'type'     => 'css',
				'element'  => '.onestore-footer-bottom-bar-row',
				'property' => 'margin',
				'pattern'  => '0 -$',
			),
			array(
				'type'     => 'css',
				'element'  => '.onestore-footer-bottom-bar .onestore-footer-menu .menu-item',
				'property' => 'padding',
				'pattern'  => '0 $',
			),
		),
	)
);


OneStore_Customizer::add_field(
	'footer_bottom_bar',
	array(
		'control_class' => 'css',
		'selector' => '.page-layout-boxed .onestore-footer .onestore-footer-bottom-bar-inner, .page-layout-full-width .onestore-footer .onestore-footer-bottom-bar-inner', // apply for css tyle only.
		'devices' => false, 
		'states' => '',
		'control' => array(
			'label'      => esc_html__( 'Wrapper', 'onestore' ),
		),
		'css_rules' => array(),
		'hide_groups' => array(
			'outline',
			'sizing',
			'extra',
		),
		'disabled_props' => array( 'font_family' ),
	)
);

OneStore_Customizer::add_field(
	'footer_bottom_bar_link',
	array(
		'control_class' => 'css',
		'selector' => array(
			'default' => '.onestore-footer .onestore-footer-bottom-bar-inner a',
			'hover' => '.onestore-footer .onestore-footer-bottom-bar-inner a:hover',
		),
		'devices' => false, 
		'states' => array(
			'default' => esc_html__( 'Default', 'onestore' ),
			'hover' => esc_html__( 'Hover', 'onestore' ),
		),
		'control' => array(
			'label' => esc_html__( 'Link', 'onestore' ),
		),
		'css_rules' => array(),
		'hide_groups' => array(
			'outline',
			'sizing',
			'extra',
			'spacing',
			'shadow',
		),
		'disabled_props' => array( 'font_family' ),
	)
);




OneStore_Customizer::section_end();
OneStore_Customizer::no_panel();
