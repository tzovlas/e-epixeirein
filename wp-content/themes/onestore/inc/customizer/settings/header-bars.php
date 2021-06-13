<?php

OneStore_Customizer::set_wp_customize();
OneStore_Customizer::no_panel();

foreach ( array( 'top_bar', 'main_bar', 'bottom_bar' ) as $bar ) {
	$section = 'onestore_section_header_' . $bar;
	$slug = str_replace( '_', '-', $bar );
	OneStore_Customizer::section_start( $section );
	OneStore_Customizer::set_current_priority( 20 );


	if ( $bar !== 'main_bar' ) {
		$id = 'header_' . $bar . '_merged';
		OneStore_Customizer::add_field(
			$id,
			array(
				'control_class' => 'toggle',
				'control' => array(
					'label'       => esc_html__( 'Merge inside Main Bar wrapper', 'onestore' ),
				),

			)
		);

		$id = 'header_' . $bar . '_merged_gap';

		OneStore_Customizer::add_field(
			$id,
			array(
				'control_class' => 'dimension',
				'control' => array(
					'label'       => esc_html__( 'Gap with Main Bar content', 'onestore' ),
					'units'       => array(
						'px' => array(
							'min'   => 0,
							'step'  => 1,
						),
					),
				),
				'live_css' => array(
					array(
						'type'     => 'css',
						'element'  => '.header-main-bar.header-main-bar-with-' . $slug . ' .header-main-bar-row',
						'property' => 'padding-' . ( 'top_bar' === $bar ? 'top' : 'bottom' ),
					),
				),

			)
		);


	} // end if not main bar.


	$id = 'header_' . $bar . '_container';
	OneStore_Customizer::add_field(
		$id,
		array(
			'control_class' => 'radio_image',
			'control' => array(
				'label'       => esc_html__( 'Layout', 'onestore' ),
				'description'       => esc_html__( 'Only apply for boxed layout.', 'onestore' ),
				'choices'     => array(
					'default'    => array(
						'label' => esc_html__( 'Normal', 'onestore' ),
						'image' => ONESTORE_IMAGES_URL . '/customizer/header-container--default.svg',
					),
					'full-width' => array(
						'label' => esc_html__( 'Full width', 'onestore' ),
						'image' => ONESTORE_IMAGES_URL . '/customizer/header-container--full-width.svg',
					),
				),
			),
			'live_css' => array(
				array(
					'type'     => 'class',
					'element'  => '.header-' . $slug,
					'pattern'  => 'main-section-$',
				),
			),
		)
	);

	if ( 'main_bar' == $bar ) {
		OneStore_Customizer::add_field(
			'header_' . $bar . '_sticky',
			array(
				'control_class' => 'toggle',
				'control' => array(
					'label'       => esc_html__( 'Enable Sticky', 'onestore' ),
				),
			)
		);
	}


	OneStore_Customizer::add_field(
		'header_' . $bar . '_items_gutter',
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
					'element'  => '.header-' . $slug . ' .header-column > *',
					'property' => 'padding',
					'pattern'  => '0 $',
				),
				array(
					'type'     => 'css',
					'element'  => '.header-' . $slug . '-row',
					'property' => 'margin',
					'pattern'  => '0 -$',
				),
			),
		)
	);
	OneStore_Customizer::add_field(
		'header_' . $bar . '_icon_size',
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
					'element'  => '.header-' . $slug . ' .onestore-menu-icon',
					'property' => 'font-size',
				),
				array(
					'type'     => 'css',
					'element'  => '.header-' . $slug . ' .onestore-menu-icon.x1_5',
					'property' => 'font-size',
					'pattern'  => 'calc( 1.5 * $ )',
				),
				array(
					'type'     => 'css',
					'element'  => '.header-' . $slug . ' .onestore-menu-icon.x2',
					'property' => 'font-size',
					'pattern'  => 'calc( 2 * $ )',
				),

				array(
					'type'     => 'css',
					'element'  => '.header-' . $slug . ' .onestore-menu-icon.x3',
					'property' => 'font-size',
					'pattern'  => 'calc( 3 * $ )',
				),

				array(
					'type'     => 'css',
					'element'  => '.header-' . $slug . ' .image-size',
					'property' => 'width',
				),
				array(
					'type'     => 'css',
					'element'  => '.header-' . $slug . ' .image-size',
					'property' => 'height',
				),

				array(
					'type'     => 'css',
					'element'  => '.header-' . $slug . ' .image-size.x1_5',
					'property' => 'width',
					'pattern'  => 'calc( 1.5 * $ )',
				),
				array(
					'type'     => 'css',
					'element'  => '.header-' . $slug . ' .image-size.x1_5',
					'property' => 'height',
					'pattern'  => 'calc( 1.5 * $ )',
				),
				array(
					'type'     => 'css',
					'element'  => '.header-' . $slug . ' .image-size.x2',
					'property' => 'width',
					'pattern'  => 'calc( 2 * $ )',
				),
				array(
					'type'     => 'css',
					'element'  => '.header-' . $slug . ' .image-size.x2',
					'property' => 'height',
					'pattern'  => 'calc( 2 * $ )',
				),
			),
		)
	);


	OneStore_Customizer::add_field(
		'header_' . $bar,
		array(
			'control_class' => 'css',
			'selector' => '#header .header-' . $slug . '-inner',
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
						'element'  => '#header .header-' . $slug . ',
							.header-' . $slug . ' .menu a,
							.header-' . $slug . ' .menu-item,
							.header-' . $slug . ' .button,
							.header-' . $slug . ' .action-toggle,
							.header-' . $slug . ' a',
						'property' => 'color',
					),
				),
				'height' => array(
					array(
						'type'     => 'css',
						'element'  => '#header .header-' . $slug . ', #header .header-' . $slug . '.scroll-fixed >.section-inner ',
						'property' => 'height',
					),
				),

			),
			'control' => array(
				'label'      => esc_html__( 'Wrapper Styling', 'onestore' ),
			),
		)
	);

	OneStore_Customizer::add_field(
		'header_' . $bar . '_link',
		array(
			'control_class' => 'css',
			'selector' => array(
				'default' => '.header-' . $slug . ' .wrapper a,
					.header-' . $slug . ' .wrapper .menu-item, 
					.header-' . $slug . ' .wrapper button, 
					.header-' . $slug . ' .wrapper .button, 
					.header-' . $slug . ' .wrapper .action-toggle',

				'hover' => '.header-' . $slug . ' .wrapper a:hover,
					.header-' . $slug . ' .wrapper .menu-item:hover, 
					.header-' . $slug . ' .wrapper button:hover, 
					.header-' . $slug . ' .wrapper .button:hover, 
					.header-' . $slug . ' .wrapper .action-toggle:hover',
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
				'min_height',
				'max_height',
				'height'
			),
			'control' => array(
				'label'      => esc_html__( 'Button & Link', 'onestore' ),
			),
		)
	);

	OneStore_Customizer::add_field(
		'header_' . $bar . '_menu',
		array(
			'control_class' => 'css',
			'selector' => array(
				'default' => '.header-' . $slug . ' .menu > .menu-item > .menu-item-link',
				'highlight' => '.header-' . $slug . ' .menu > .menu-item.current-menu-item > .menu-item-link,
					.header-' . $slug . ' .menu > .menu-item:hover > .menu-item-link,
					.header-' . $slug . ' .menu > .menu-item.focus > .menu-item-link
					.header-' . $slug . ' .menu > .menu-item:focus > .menu-item-link',
			), // apply for css tyle only.
			'devices' => false, // or array( 'desktop', 'tablet', 'mobile );
			'states' => array(
				'default' => esc_html__( 'Default', 'onestore' ),
				'highlight' => esc_html__( 'Highlight', 'onestore' ),
			), // or array( 'desktop', 'tablet', 'mobile );
			'hide_groups' => array(
				'extra',
				'shadow',
			),
			
			'control' => array(
				'label'      => esc_html__( 'Menu', 'onestore' ),
			),
			'css_rules' => array(
				'margin' => array(
					array(
						'type'     => 'css',
						'element'  => array(
							'default' => '.header-' . $slug . ' .menu > .menu-item',
							'highlight' => '.header-' . $slug . ' .menu > .menu-item.current-menu-item,
							.header-' . $slug . ' .menu > .menu-item:hover,
							.header-' . $slug . ' .menu > .menu-item.forcus,
							.header-' . $slug . ' .menu > .menu-item:focus',
						),
						'property' => 'padding',
					),
				),
			),
		)
	);

	$submenu_ul = '.header-' . $slug . ' .menu .menu-item > .sub-menu';
	OneStore_Customizer::add_field(
		'header_' . $bar . '_submenu',
		array(
			'control_class' => 'css',
			'selector' => array(
				'default' => $submenu_ul,
				'item' => '.header-' . $slug . ' .menu  .sub-menu > .menu-item',
				'hover' => '.header-' . $slug . ' .menu  .sub-menu > .menu-item:hover,
						.header-' . $slug . ' .menu  .sub-menu > .menu-item:focus,
						.header-' . $slug . ' .menu  .sub-menu > .menu-item.focus',
			),
			'devices' => false,
			'hide_groups' => array(
				'outline',
			),
			'disabled_props' => array(
				'font_family',
				'min_height',
				'max_height',
				'height'
			),
			'states' => array(
				'default'      => esc_html__( 'Wraper', 'onestore' ),
				'item'      => esc_html__( 'Item', 'onestore' ),
				'hover'      => esc_html__( 'Item Highligh', 'onestore' ),
			),
			'control' => array(
				'label'      => esc_html__( 'Submenu', 'onestore' ),
			),
		)
	);

} // end loop  bars;



OneStore_Customizer::section_end();
