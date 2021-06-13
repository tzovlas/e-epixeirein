<?php
OneStore_Customizer::set_wp_customize();
OneStore_Customizer::no_panel();

OneStore_Customizer::section_start( 'onestore_section_canvas', esc_html__( 'Off Canvas', 'onestore' ), 173 );
OneStore_Customizer::set_current_priority( 20 );


OneStore_Customizer::add_field(
	'canvas_side_width',
	array(
		'control_class' => 'slider',
		'devices' => 'all',
		'control' => array(
			'label'       => esc_html__( 'Canvas Width', 'onestore' ),
			'description'       => esc_html__( 'Apply for left, right canvas', 'onestore' ),
			'units'       => array(
				'px' => array(
					'min'  => 0,
					'max'  => 1300,
					'step' => 1,
				),
				'%' => array(
					'min'  => 0,
					'max'  => 100,
					'step' => 1,
				),
				'em' => array(
					'min'  => 0,
					'max'  => 50,
					'step' => 1,
				),
			),
		),
		'live_css' => array(
			array(
				'type'     => 'css',
				'element'  => ':root',
				'property' => '--popup-side-width',
			),
		),
	)
);


OneStore_Customizer::add_field(
	'canvas_general',
	array(
		'control_class' => 'css',
		'selector' => '.popup .popup-content', // apply for css tyle only.
		'devices' => 'all', // or array( 'desktop', 'tablet', 'mobile );
		'states' => 'all', // or array( 'desktop', 'tablet', 'mobile );
		'hide_groups' => array(
			'sizing',
			'extra',
			'border',
			'shadow',
			'outline',
		),
		'control' => array(
			'label'      => esc_html__( 'Wrapper', 'onestore' ),
		),
		'css_rules' => array(
			'margin' => array(
				array(
					'element'  => '.popup .popup-content .popup-inner',
				),
			),
			'padding' => array(
				array(
					'element'  => '.popup .popup-content .popup-inner',
				),
			),
		),
	)
);

OneStore_Customizer::add_field(
	'canvas_header',
	array(
		'control_class' => 'css',
		'selector' => '.popup .popup-content .popup-heading, .popup .popup-content .popup-heading h2, .popup .popup-content .popup-close', // apply for css tyle only.
		'devices' => 'all', // or array( 'desktop', 'tablet', 'mobile );
		'states' => 'all', // or array( 'desktop', 'tablet', 'mobile );
		'hide_groups' => array(
			'outline',
			'extra',
		),
		'control' => array(
			'label'      => esc_html__( 'Header', 'onestore' ),
		),
		'css_rules' => array(
			'font_size' => array(
				array(
					'element'  => '.popup .popup-content .popup-heading, 
					.popup .popup-content .popup-heading h2',
				),
				array(
					'element'  => '.popup .popup-content .popup-heading img',
					'property'  => 'height',
				),
			),
		),
		'disabled_props' => array(
			'width',
			'min_width',
			'max_width',
			'height',
			'max_height',
		),
	)
);

OneStore_Customizer::add_field(
	'canvas_link',
	array(
		'control_class' => 'css',
		'selector' => array(
			'default' => '.popup .popup-content a',
			'hover' => '.popup .popup-content a:hover',
		),
		'devices' => false, // or array( 'desktop', 'tablet', 'mobile );
		'states' => array(
			'default' => esc_html__( 'Default', 'onestore' ),
			'hover' => esc_html__( 'Hover', 'onestore' ),
		), // or array( 'desktop', 'tablet', 'mobile );
		'hide_groups' => array(
			'sizing',
			'extra',
		),
		'disabled_props' => array(
			'font_family',
		),
		'control' => array(
			'label'      => esc_html__( 'Link', 'onestore' ),
		),
	)
);


OneStore_Customizer::section_end();
OneStore_Customizer::no_panel();

