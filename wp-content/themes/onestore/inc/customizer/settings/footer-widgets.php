<?php


OneStore_Customizer::set_wp_customize();
OneStore_Customizer::no_panel();
OneStore_Customizer::section_start( 'onestore_section_footer_widgets_bar' );
OneStore_Customizer::set_current_priority( 20 );


OneStore_Customizer::add_field(
	'footer_widgets_bar_columns_gutter',
	array(
		'control_class' => 'slider',
		'devices' => 'all',
		'control' => array(
			'label'       => esc_html__( 'Columns gutter', 'onestore' ),
			'units'       => array(
				'em' => array(
					'min'  => 0,
					'step' => 0.5,
				),
				'px' => array(
					'min'  => 0,
					'max'  => 40,
					'step' => 1,
				),
			),
		),
		'live_css' => array(
			array(
				'type'     => 'css',
				'element'  => '.footer-widget-cols.device-columns .col',
				'property' => 'padding',
				'pattern'  => '0 $',
			),
			array(
				'type'     => 'css',
				'element'  => '.footer-widget-cols.device-columns',
				'property' => 'margin-left',
				'pattern'  => '-$',
			),
			array(
				'type'     => 'css',
				'element'  => '.footer-widget-cols.device-columns',
				'property' => 'margin-right',
				'pattern'  => '-$',
			),
		),
	)
);


OneStore_Customizer::add_field(
	'footer_widgets_bar_meta_border_color',
	array(
		'control_class' => 'color',
		'devices' => false,
		'control' => array(
			'label'       => esc_html__( 'Meta Border Color', 'onestore' ),
			'description'       => esc_html__( 'Apply for items inside widget content.', 'onestore' ),
		),
		'live_css' => array(
			array(
				'type'     => 'css',
				'element'  => '.footer-widget-cols',
				'property' => '--color-border',
			),
		),
	)
);



OneStore_Customizer::add_field(
	'footer_bottom',
	array(
		'control_class' => 'css',
		'selector' => '.page-layout-boxed .onestore-footer .onestore-footer-widgets-bar-inner, .page-layout-full-width .onestore-footer .onestore-footer-widgets-bar-inner', // apply for css tyle only.
		'devices' => 'all', // or array( 'desktop', 'tablet', 'mobile );
		'states' => '', // or array( 'desktop', 'tablet', 'mobile );
		'control' => array(
			'label'      => esc_html__( 'Wrapper Styling', 'onestore' ),
		),
		'css_rules' => array(),
		'hide_groups' => array(
			'typo',
			'outline',
			'sizing',
			'extra',
		),
	)
);

OneStore_Customizer::add_field(
	'footer_bottom_widgets',
	array(
		'control_class' => 'css',
		'selector' => '.onestore-footer-widgets-bar-inner .widget', // apply for css tyle only.
		'selector' => array(
			'default' => '.onestore-footer-widgets-bar-inner .widget', // apply for css tyle only.
			'heading' => '.onestore-footer-widgets-bar-inner .widget .widget-title', // apply for css tyle only.
		),
		'devices' => 'all', // all or array( 'desktop', 'tablet', 'mobile );
		'states' => array(
			'default' => esc_html__( 'Widget Content', 'onestore' ),
			'heading' => esc_html__( 'Widget Title', 'onestore' ),
		), // or array( 'desktop', 'tablet', 'mobile );
		'hide_groups' => array(
			'extra',
			'sizing',
		),
		'control' => array(
			'label'      => esc_html__( 'Widgets', 'onestore' ),
		),
	)
);

OneStore_Customizer::add_field(
	'footer_bottom_widget_link',
	array(
		'control_class' => 'css',
		'selector' => array(
			'default' => ' .onestore-footer-widgets-bar-inner a',
			'hover' => '.onestore-footer-widgets-bar-inner a:hover',
		),
		'devices' => false,
		'states' => array(
			'default' => esc_html__( 'Links', 'onestore' ),
			'hover' => esc_html__( 'Hover', 'onestore' ),
		), // or array( 'desktop', 'tablet', 'mobile );
		'control' => array(
			'label'      => esc_html__( 'Links', 'onestore' ),
		),
		'css_rules' => array(),
		'hide_groups' => array(
			'sizing',
			'extra',
			'background',

		),
		'disabled_props' => array( 'font_family' ),
	)
);



for ( $i = 1; $i <= 6; $i++ ) {

	OneStore_Customizer::add_field(
		'footer_bottom_w_col_' . $i . '_width',
		array(
			'control_class' => 'slider',
			'devices' => 'all',
			'control' => array(
				'label'       => sprintf( esc_html__( 'Column %s Width', 'onestore' ), $i ),
				'units'       => array(
					'%' => array(
						'min'   => 0,
						'step'  => 1,
						'max'  => 100,
					),
				),
			),
			'live_css' => array(
				array(
					'type'     => 'css',
					'element'  => '.onestore-footer-section .footer-widget-cols .widgets-column-' . $i,
					'property' => 'width',
				),
			),
			'condition' => array(
				array(
					'setting'  => 'footer_widgets_bar',
					'operator' => '>=',
					'value'    => $i,
				),
			),
		)
	);
}

OneStore_Customizer::section_end();
OneStore_Customizer::no_panel();
