<?php
OneStore_Customizer::set_wp_customize();
OneStore_Customizer::panel_start( 'onestore_panel_header' );

foreach ( OneStore_Customizer::get_mobile_header_rows() as $row_id => $label ) {

	$section = 'onestore_section_header_mobile_' . $row_id . '_bar';
	$slug = str_replace( '_', '-', $row_id );
	OneStore_Customizer::section_start( $section, $label, 20 );
	OneStore_Customizer::set_current_priority( 20 );

	if ( 'main' == $row_id ) {
		OneStore_Customizer::add_field(
			'header_mobile_' . $row_id . '_bar_sticky',
			array(
				'control_class' => 'toggle',
				'control' => array(
					'label'       => esc_html__( 'Enable Sticky', 'onestore' ),
				),

			)
		);
	}

	OneStore_Customizer::add_field(
		'header_mobile_' . $row_id . '_bar_items_gutter',
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
					'element'  => '.header-mobile-' . $slug . '-bar .header-column > *',
					'property' => 'padding',
					'pattern'  => '0 $',
				),
				array(
					'type'     => 'css',
					'element'  => '.header-mobile-' . $slug . '-bar .header-row',
					'property' => 'margin',
					'pattern'  => '0 -$',
				),
				array(
					'type'     => 'css',
					'element'  => '.header-mobile-' . $slug . '-bar .header-menu .menu-item',
					'property' => 'padding',
					'pattern'  => '0 $',
				),
			),

		)
	);

	OneStore_Customizer::add_field(
		'header_mobile_' . $row_id . '_bar_icon_size',
		array(
			'control_class' => 'slider',
			'control' => array(
				'label'       => esc_html__( 'Icon Size', 'onestore' ),
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
					'element'  => '.header-mobile-' . $slug . '-bar .onestore-menu-icon',
					'property' => 'font-size',
				),
				array(
					'type'     => 'css',
					'element'  => '.header-mobile-' . $slug . '-bar .onestore-menu-icon.x1_5',
					'property' => 'font-size',
					'pattern'  => 'calc( 1.5 * $ )',
				),
				array(
					'type'     => 'css',
					'element'  => '.header-mobile-' . $slug . '-bar .onestore-menu-icon.x2',
					'property' => 'font-size',
					'pattern'  => 'calc( 2 * $ )',
				),

				array(
					'type'     => 'css',
					'element'  => '.header-mobile-' . $slug . '-bar .onestore-menu-icon.x3',
					'property' => 'font-size',
					'pattern'  => 'calc( 3 * $ )',
				),

				array(
					'type'     => 'css',
					'element'  => '.header-mobile-' . $slug . '-bar .image-size',
					'property' => 'width',
				),
				array(
					'type'     => 'css',
					'element'  => '.header-mobile-' . $slug . '-bar .image-size',
					'property' => 'height',
				),

				array(
					'type'     => 'css',
					'element'  => '.header-mobile-' . $slug . '-bar .image-size.x1_5',
					'property' => 'width',
					'pattern'  => 'calc( 1.5 * $ )',
				),
				array(
					'type'     => 'css',
					'element'  => '.header-mobile-' . $slug . '-bar .image-size.x1_5',
					'property' => 'height',
					'pattern'  => 'calc( 1.5 * $ )',
				),
				array(
					'type'     => 'css',
					'element'  => '.header-mobile-' . $slug . '-bar .image-size.x2',
					'property' => 'width',
					'pattern'  => 'calc( 2 * $ )',
				),
				array(
					'type'     => 'css',
					'element'  => '.header-mobile-' . $slug . '-bar .image-size.x2',
					'property' => 'height',
					'pattern'  => 'calc( 2 * $ )',
				),
			),

		)
	);


	OneStore_Customizer::add_field(
		'header_mobile_' . $row_id,
		array(
			'control_class' => 'css',
			'selector' => '#mobile-header .header-mobile-' . $slug . '-bar-inner',
			'devices' => false, // or array( 'desktop', 'tablet', 'mobile );
			'states' => '', // or array( 'desktop', 'tablet', 'mobile );
			'hide_groups' => array(
				'extra',
			),
			'disabled_props' => array(
				'width',
				'min_width',
				'max_width',
			),
			'css_rules' => array(
				'color' => array(
					array(
						'type'     => 'css',
						'element'  => '#mobile-header .header-mobile-' . $slug . '-bar,
							.header-mobile-' . $slug . '-bar .menu a,
							.header-mobile-' . $slug . '-bar .menu-item,
							.header-mobile-' . $slug . '-bar .button,
							.header-mobile-' . $slug . '-bar .action-toggle,
							.header-mobile-' . $slug . '-bar a',
						'property' => 'color',
					),
				),
				'height' => array(
					array(
						'type'     => 'css',
						'element'  => '#mobile-header .header-mobile-' . $slug . '-bar, #mobile-header .header-mobile-' . $slug . '-bar.scroll-fixed >.section-inner ',
						'property' => 'height',
					),
				),

			),
			'control' => array(
				'label'      => esc_html__( 'Wrapper', 'onestore' ),
			),
		)
	);

	OneStore_Customizer::add_field(
		'header_mobile_' . $row_id . '_link',
		array(
			'control_class' => 'css',
			'selector' => array(
				'default' => '.header-mobile-' . $slug . '-bar .wrapper a,
					.header-mobile-' . $slug . '-bar .wrapper .menu-item, 
					.header-mobile-' . $slug . '-bar .wrapper button, 
					.header-mobile-' . $slug . '-bar .wrapper .button, 
					.header-mobile-' . $slug . '-bar .wrapper .action-toggle',

				'hover' => '.header-mobile-' . $slug . '-bar .wrapper a:hover,
					.header-mobile-' . $slug . '-bar .wrapper .menu-item:hover, 
					.header-mobile-' . $slug . '-bar .wrapper button:hover, 
					.header-mobile-' . $slug . '-bar .wrapper .button:hover, 
					.header-mobile-' . $slug . '-bar .wrapper .action-toggle:hover',
			),
			'devices' => false, // or array( 'desktop', 'tablet', 'mobile );
			'states' => array(
				'default' => __( 'Default', 'onestore' ),
				'hover' => __( 'Active/Hover', 'onestore' ),
			), // or array( 'desktop', 'tablet', 'mobile );
			'hide_groups' => array(
				'extra',
			),
			'disabled_props' => array(
				'font_family',
				'width',
				'min_width',
				'max_width',
			),
			'control' => array(
				'label'      => esc_html__( 'Button & Link', 'onestore' ),
			),
		)
	);



	OneStore_Customizer::section_end();

}

OneStore_Customizer::no_panel();
